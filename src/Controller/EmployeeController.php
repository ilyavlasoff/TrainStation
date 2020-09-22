<?php

namespace App\Controller;

use App\Entity\Monitoring;
use App\Entity\Train;
use App\Entity\Voyage;
use App\Form\MonitoringForm;
use App\Form\VoyageCreationForm;
use App\Repository\MonitoringRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    /**
     * @Route("/employee/monitoring", name="monitoring_list")
     */
    public function displayMonitoringPage(Request $request) {
        /** @var MonitoringRepository $monitoringRepos */
        $monitoringRepos = $this->getDoctrine()->getRepository(Monitoring::class);
        /** @var Train[] $trains */
        $trains = $this->getDoctrine()->getRepository(Train::class)->findAll();
        $trainsList = [];
        $trainsList['Все'] = null;
        foreach ($trains as $train) {
            $trainsList[$train->getId() . ' (' . $train->getRoute() . ')'] = $train->getId();
        }
        $countTypes = [
            'Все' => null,
            '10' => 10,
            '50' => 50,
            '100' => 100,
            '500' => 500,
            '1000' => 1000
        ];
        $form = $this->createFormBuilder()
            ->add('date', DateType::class, [
                'label' => 'Дата',
                'widget' => 'single_text',
            ])
            ->add('train', ChoiceType::class, [
                'label' => 'Номер поезда',
                'choices' =>$trainsList
            ])
            ->add('count', ChoiceType::class, [
                'label' => 'Количество',
                'choices' => $countTypes
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Найти'
            ])
            ->getForm();
        $form->handleRequest($request);

        $addMonitoringForm = $this->createFormBuilder()
            ->add('submit', SubmitType::class, ['label' => 'Добавить'])
            ->getForm();
        $addMonitoringForm->handleRequest($request);
        if ($addMonitoringForm->isSubmitted() && $addMonitoringForm->isValid()) {
            return new RedirectResponse($this->generateUrl('add-monitoring'));
        }

        $monitoringHistory = $monitoringRepos->getMonitoringHistory();
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $train = null;
            if ($formData['train']) {
                $train = $this->getDoctrine()->getRepository(Train::class)->find($formData['train']);
            }
            $monitoringHistory = $monitoringRepos->getMonitoringHistory($train, $formData['date'], $formData['count']);
        }

        return $this->render('pages/employee_monitoring_page.html.twig', [
            'form' => $form->createView(),
            'addMonitoringForm' => $addMonitoringForm->createView(),
            'monitoringHistory' => $monitoringHistory
        ]);
    }

    /**
     * @Route("/employee/voyage-list", name="emp_voyage_list")
     */
    public function displayVoyagesPage(Request  $request) {
        $form = $this->createFormBuilder()
            ->add('submit', SubmitType::class, [
                'label' => 'Добавить'
            ])
            ->getForm();
        $form->handleRequest($request);

        /** @var Voyage[] $voyagesList */
        $voyagesList = $this->getDoctrine()->getRepository(Voyage::class)->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            return new RedirectResponse($this->generateUrl('add_voyage'));
        }
        return $this->render('pages/employee_voyages_management.html.twig', [
            'form' => $form->createView(),
            'voyages' => $voyagesList
        ]);
    }

    /**
     * @Route("/employee/voyage/add", name="add_voyage")
     */
    public function addVoyage(Request $request) {
        $voyage = new Voyage();
        $form = $this->createForm(VoyageCreationForm::class, $voyage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($voyage);
            $em->flush();
            return new RedirectResponse($this->generateUrl('emp_voyage_list'));
        }

        return $this->render('pages/employee_add_voyage.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/employee/add-monitoring", name="add-monitoring")
     */
    public function addMonitoring(Request $request)
    {
        $monitoring = new Monitoring();
        $form = $this->createForm(MonitoringForm::class, $monitoring);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($monitoring);
            $em->flush();
            return new RedirectResponse($this->generateUrl('monitoring_list'));
        }
        return $this->render('pages/employee_add_monitoring.html.twig', [
            'form' => $form->createView()
        ]);
    }

}