<?php

namespace App\Controller;

use App\Entity\Train;
use App\Entity\Voyage;
use App\Repository\TrainRepository;
use App\Repository\VoyageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RoutesListController extends AbstractController
{
    /**
     * @Route("/routes-list", name="routes_list")
     */
    public function displayRoutesListPage() {
        /** @var TrainRepository $trainsRepos */
        $trainsRepos = $this->getDoctrine()->getRepository(Train::class);
        $trains = $trainsRepos->findAll();
        return $this->render('pages/routes_list.html.twig', ['trains' => $trains]);
    }

    /**
     * @Route("/voyages-list", name="voyages_list")
     */
    public function displayVoyagesList(Request $request) {

        /** @var VoyageRepository $voyagesRepos */
        $voyagesRepos = $this->getDoctrine()->getRepository(Voyage::class);

        $form = $this->createFormBuilder()
            ->add('date', DateType::class, [
                'label' => 'Дата поездки',
                'widget' => 'single_text',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Поиск'
            ])
            ->getForm();
        $form->handleRequest($request);

        $currentDateTime = new \DateTime('now');
        if ($form->isSubmitted() && $form->isValid()) {
            $currentDateTime = $form->get('date')->getData();
        }
        else {
            $form->get('date')->setData(new \DateTime('now'));
        }

        $voyages = $voyagesRepos->getVoyagesList($currentDateTime);

        return $this->render('pages/voyages_list.html.twig', [
            'form' => $form->createView(),
            'voyages' => $voyages
        ]);
    }
}