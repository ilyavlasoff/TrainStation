<?php

namespace App\Controller;

use App\Entity\Station;
use App\Entity\Ticket;
use App\Entity\TicketStatus;
use App\Entity\Train;
use App\Entity\User;
use App\Entity\Voyage;
use App\Entity\Wagon;
use App\Entity\WagonType;
use App\Form\OrderCreationForm;
use App\Repository\TrainRepository;
use App\Repository\VoyageRepository;
use App\Repository\WagonRepository;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class BuyTicketController extends AbstractController {

    /**
     * @Route("/find-voyages", name="buy_ticket")
     */
    public function displayFindTicketPage() {
        $availableStations = $this->getDoctrine()->getRepository(Station::class)->findAll();
        return $this->render('pages/buy_ticket_page.html.twig', [
            'availableStations' => $availableStations
        ]);
    }

    /**
     * @Route("/buy", name="create_ticket_order")
     */
    public function displayBuyTicketPage(Request $request) {
        $voyageId = $request->query->get('voyage');

        /** @var Voyage $voyage */
        $voyage = $this->getDoctrine()->getRepository(Voyage::class)->find($voyageId);
        if (! $voyage) {
            throw new \Exception('Страница не найдена');
        }

        /** @var TrainRepository $trainRepository */
        $trainRepository = $this->getDoctrine()->getRepository(Train::class);

        /** @var WagonType[] $availableWagonTypes */
        $availableWagonTypes = $trainRepository->getAvailableWagonTypes($voyage->getTrain());
        $availableWagonTypesList = [];
        foreach ($availableWagonTypes as $availableWagonType) {
            $wagonTypeDescription = $availableWagonType->getTypeDescription();
            $availableWagonTypesList[$wagonTypeDescription] = $availableWagonType->getId();
        }
        $paymentTypes = [
            'Картой онлайн' => 'onlineCard',
            'Яндекс.Деньги' => 'yandexMoney',
            'WebMoney' => 'webMoney'
        ];

        /** @var Station[] $availableStations */
        $availableStations = $trainRepository->getAvailableStations($voyage->getTrain());

        $availableStationList = [];
        foreach ($availableStations as $availableStation) {
            $availableStationList[$availableStation->getAddress()] = $availableStation->getId();
        }

        $form = $this->createForm(OrderCreationForm::class, null, [
            'wagonTypes' => $availableWagonTypesList,
            'paymentTypes' => $paymentTypes,
            'stationsOptions' => $availableStationList
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $formData = $form->getData();

            /** @var Ticket $ticket */
            $ticket = new Ticket();

            /** @var Wagon $wagon */
            $wagon = $this->getDoctrine()->getRepository(Wagon::class)->find(12);
            $ticket->setWagon($wagon);

            $wagonTypeRepos = $this->getDoctrine()->getRepository(WagonType::class);

            /** @var WagonType $wagonType */
            $wagonType = $wagonTypeRepos->find($formData['wagonType']);
            $price = $wagonType->getPrice() - min(0.5 * $wagonType->getPrice(), $formData['bonusesToCheckout']);
            $ticket->setPrice($price);

            $routeLength = random_int(90, 1000);
            $ticket->setRouteLength($routeLength);
            $ticket->setPriceForKm($price / $routeLength);

            /** @var TicketStatus $status */
            $status = $this->getDoctrine()->getRepository
            (TicketStatus::class)->findOneBy(['statusDescription' => 'Created']);
            $ticket->setStatus($status);

            /** @var User $user */
            $user = $this->getUser();
            $ticket->setUser($user);

            $stationRepos = $this->getDoctrine()->getRepository(Station::class);
            /** @var Station $sourceStation */
            $sourceStation = $stationRepos->find($formData['sourceStation']);
            /** @var Station $destinationStation */
            $destinationStation = $stationRepos->find($formData['destinationStation']);
            $ticket->setSourceStation($sourceStation);
            $ticket->setDestinationStation($destinationStation);

            $ticket->setVoyage($voyage);
            $ticket->setPlace(101);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($ticket);
            $manager->flush();
        }

        /** @var User $user */
        $user = $this->getUser();
        $maxBonusesCount = $user->getBonuses()->getQuantity();

        return $this->render('pages/create_order_page.html.twig', [
            'ticketInformationForm' => $form->createView(),
            'voyage' => $voyage,
            'maxBonuses' => $maxBonusesCount
        ]);
    }

    /**
     * @Route("/find_ticket", name="find_ticket_options", methods={"POST"})
     */
    public function findTicketOptions(Request $request) {
        $source = $request->request->get('from');
        $destination = $request->request->get('to');
        $date = $request->request->get('date');

        if(!($source && $destination && $date && ($date = \DateTime::createFromFormat('Y-m-d', $date)))) {
            return new JsonResponse(json_encode([
                'error' => 'Bad arguments'
            ]), Response::HTTP_BAD_REQUEST);
        }
        try
        {
            /** @var VoyageRepository $repos */
            $repos = $this->getDoctrine()->getRepository(Voyage::class);
            $result = $repos->getAvailableVoyages($source, $destination, $date);
            return new JsonResponse(json_encode([
                'success' => true,
                'data' => $result
            ]));
        }
        catch (\Exception $e) {
            return new JsonResponse(json_encode([
                'success' => false,
                'error' => 'Internal server error',
                'info' => $e->getMessage()
            ]), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/available_wagons", name="get_available_trains", methods={"POST"})
     */
    public function getWagonListByTypeOfTrain(Request $request) {

        $wagonTypeId = $request->request->get('type');
        $trainId = $request->request->get('train');

        if (! ($wagonTypeId && $trainId)) {
            return new JsonResponse(json_encode([
                'success' => false,
                'error' => 'Invalid arguments'
            ]), Response::HTTP_BAD_REQUEST);
        }

        /** @var WagonRepository $wagonRepos */
        $wagonRepos = $this->getDoctrine()->getRepository(Wagon::class);

        /** @var Train $train */
        $train = $this->getDoctrine()->getRepository(Train::class)->find($trainId);
        /** @var WagonType $wagonType */
        $wagonType = $this->getDoctrine()->getRepository(WagonType::class)->find($wagonTypeId);

        if (! ($wagonType && $train)) {
            return new JsonResponse(json_encode([
                'success' => false,
                'error' => 'Invalid arguments'
            ]), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $availableWagons = $wagonRepos->getWagonsByType($train, $wagonType);

        return new JsonResponse(json_encode([
            'success' => true,
            'data' => $availableWagons
        ]));
    }

}