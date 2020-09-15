<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\User;
use App\Form\PasswordRestorationForm;
use App\Form\PersonalDataEditForm;
use App\Repository\TicketRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PersonalPageController extends AbstractController {
    /**
     * @Route("/personal", name="user_personal_page")
     */
    public function displayPersonalUserPage() {
        return $this->render('pages/user_personal_page.html.twig', [
            'benefits' => [],
            'bonuses' => 100
        ]);
    }

    /**
     * @Route("/mytrips", name="user_personal_trips")
     */
    public function displayPersonalUserTrips() {
        $entityManager = $this->getDoctrine()->getManager();

        /** @var TicketRepository $ticketRepository */
        $ticketRepository = $entityManager->getRepository(Ticket::class);

        /** @var User $user */
        $user = $this->getUser();

        $ordersInfo = $ticketRepository->getTicketList($user);

        return $this->render('pages/orders_history_page.html.twig', [
            'ordersInfo' => $ordersInfo
        ]);
    }

    /**
     * @Route("/personal/edit", name="edit_personal_data")
     */
    public function editPersonalUserData(UserPasswordEncoderInterface $encoder, Request $request) {
        /** @var User $user */
        $user = $this->getUser();
        $passwordRestorationForm = $this->createForm(PasswordRestorationForm::class);
        $passwordRestorationForm->handleRequest($request);

        $personalDataForm = $this->createForm(PersonalDataEditForm::class, $user);
        $personalDataForm->handleRequest($request);

        $renderArgs = [
            'passwordRestorationForm' => $passwordRestorationForm->createView(),
            'personalDataForm' => $personalDataForm->createView()
        ];

        if ($passwordRestorationForm->isSubmitted() && $passwordRestorationForm->isValid()) {
            $formData = $passwordRestorationForm->getData();
            $oldPassword = $formData['oldPassword'];
            $newPassword = $formData['password'];
            $checkPass = $encoder->isPasswordValid($user, $oldPassword);

            if ($checkPass) {
                $newPasswordEncoded = $encoder->encodePassword($user, $newPassword);
                $user->setPassword($newPasswordEncoded);
                $this->getDoctrine()->getManager()->flush();
                $renderArgs['message'] = 'Пароль успешно изменен';
            }
            else {
                $passwordRestorationForm->addError(new FormError('Старый пароль неверен'));
            }

        }

        if ($personalDataForm->isSubmitted() && $personalDataForm->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $renderArgs['message'] = 'Данные успешно сохранены';
        }

        return $this->render('pages/edit_personal_data.html.twig', $renderArgs);
    }
}