<?php

namespace App\Controller;
use App\Entity\Ticket;
use App\Entity\Train;
use App\Entity\Voyage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TicketFullPageController extends AbstractController
{
    /**
     * @Route("/ticket/{id}", name="ticket_full_page")
     */
    public function displayTicketFullPage(string $id) {
        $repos = $this->getDoctrine()->getRepository(Ticket::class);
        /** @var Ticket $ticket */
        $ticket = $repos->find($id);
        /** @var Voyage $voyage */
        $voyage = $ticket->getVoyage();
        /** @var Train $train */
        $train = $voyage->getTrain();

        return $this->render('pages/ticket_info_full_page.html.twig', [
            'ticket' => $ticket,
            'voyage' => $voyage,
            'train' => $train
        ]);
    }
}