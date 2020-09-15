<?php

namespace App\Controller;

use App\Entity\Station;
use App\Repository\StationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class CitySearchController extends AbstractController
{
    /**
     * @Route("/cities", name="city_search_page")
     */
    public function displayCitySearchPage(Request $request) {
        $form = $this->createFormBuilder()
            ->add('query', TextType::class)
            ->add('find', SubmitType::class, ['label' => 'Найти'])
            ->getForm();
        $form->handleRequest($request);

        /** @var StationRepository $repos */
        $repos = $this->getDoctrine()->getRepository(Station::class);
        if($form->isSubmitted() && $form->isValid()) {
            $searchArgument = $form['query']->getData();
            $citiesData = $repos->findStationByFreeTextSearch($searchArgument);
            $totalCount = count($repos->findAll());
        }
        else {
            $citiesData = $repos->findAll();
            $totalCount = count($citiesData);
        }

        return $this->render('pages/cities_list_page.html.twig', [
            'citySearchForm' => $form->createView(),
            'cities' => $citiesData,
            'cityCount' => $totalCount
        ]);
    }
}