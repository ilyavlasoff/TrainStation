<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserCredentialsForm;
use App\Security\UserAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    private const PARTIALLY_CREATED_USER_NAME = 'partially_authenticated_user';

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request,
                             UserPasswordEncoderInterface $passwordEncoder,
                             GuardAuthenticatorHandler $guardHandler,
                             UserAuthenticator $authenticator,
                             Session $session
    ): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setTwoFactorAuthentication(false);

            //$session->set(self::PARTIALLY_CREATED_USER_NAME, $user);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );

            return new RedirectResponse($this->generateUrl('provide_credentials_page', ['id' => $user->getId()]));
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/credentials/{id}", name="provide_credentials_page")
     */
    public function provideCredentials(String $id, Request $request)
    {
        $repos = $this->getDoctrine()->getRepository(User::class);
        $user = $repos->find($id);
        if (! $user) {
            return new RedirectResponse('app_register');
        }

        $form = $this->createForm(UserCredentialsForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return new RedirectResponse($this->generateUrl('app_home'));
        }

        return $this->render('registration/provide_credentials_page.html.twig', [
            'credentialsForm' => $form->createView()
        ]);
    }
}
