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
use App\Form\VoyagesSearchForm;
use App\Repository\StationRepository;
use App\Repository\TicketRepository;
use App\Repository\TrainRepository;
use App\Repository\VoyageRepository;
use App\Repository\WagonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class BuyTicketController extends AbstractController {

    /**
     * @Route("/find-voyages", name="buy_ticket")
     */
    public function displayFindTicketPage(Request $request) {

        /** @var Station[] $availableStations */
        $availableStations = $this->getDoctrine()->getRepository(Station::class)->findAll();
        $stations = [];
        foreach ($availableStations as $availableStation) {
            $stations[$availableStation->getAddress()] = $availableStation->getId();
        }

        $form = $this->createForm(VoyagesSearchForm::class, null, [
            'stations' => $stations
        ]);
        /** @var StationRepository $stationRepos */
        $stationRepos = $this->getDoctrine()->getRepository(Station::class);
        $sourceStationId = $request->query->get('from');
        if ($sourceStationId) {
            $sourceStation = $stationRepos->find($sourceStationId);
            if ($sourceStation) {
                $form->get('source')->setData($sourceStation->getId());
            }
        }
        $destinationStationId = $request->query->get('to');
        if ($destinationStationId) {
            $destinationStation = $stationRepos->find($destinationStationId);
            if ($destinationStation) {
                $form->get('destination')->setData($destinationStation->getId());
            }
        }
        $form->get('date')->setData(new \DateTime('now'));

        return $this->render('pages/buy_ticket_page.html.twig', [
            'availableStations' => $availableStations,
            'form' => $form->createView()
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
        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();

            /** @var Ticket $ticket */
            $ticket = new Ticket();

            $wagonNumber = $formData['wagonNumber'];
            /** @var Wagon $wagon */
            $wagon = $this->getDoctrine()->getRepository(Wagon::class)->find($wagonNumber);
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
            /** @var TicketRepository $ticketRepos */
            $ticketRepos = $this->getDoctrine()->getRepository(Ticket::class);
            $ticket->setPlace($ticketRepos->getNextTicketNumberInTrip($voyage, $wagon));

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($ticket);
            $manager->flush();

            return new RedirectResponse($this->generateUrl('order_creation_success', ['id' => $ticket->getId()]));
        }

        /** @var User $user */
        $user = $this->getUser();
        $maxBonusesCount =  0;
        if ($bonuses = $user->getBonuses()) {
            $maxBonusesCount = $bonuses->getQuantity();
        }

        return $this->render('pages/create_order_page.html.twig', [
            'ticketInformationForm' => $form->createView(),
            'voyage' => $voyage,
            'maxBonuses' => $maxBonusesCount
        ]);
    }

    /**
     * @Route("/order-confirm/{id}", name="order_creation_success")
     */
    public function orderConfirmation(string $id) {
        /** @var TicketRepository $ticketRepository */
        $ticketRepository = $this->getDoctrine()->getRepository(Ticket::class);
        /** @var Ticket $ticket */
        $ticket = $ticketRepository->find($id);

        if (! $ticket) {
            throw new \Exception('Not found');
        }

        $form = $this->createFormBuilder()
            ->add('redirect', SubmitType::class, [
                'label' => 'Вернуться на главную'
            ])
            ->getForm();

        if ($form->isSubmitted()) {
            return new RedirectResponse($this->generateUrl('app_home'));
        }

        return $this->render('pages/order_confirmation.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView()
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