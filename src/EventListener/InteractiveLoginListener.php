<?php

namespace App\EventListener;

use App\Controller\AuthenticationController;
use App\Entity\User;
use App\Service\EmailAuthenticatorHelper;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class InteractiveLoginListener
{
    /** @var EmailAuthenticatorHelper  */
    private $authenticationHelper;

    public function __construct(EmailAuthenticatorHelper $authenticationHelper)
    {
        $this->authenticationHelper = $authenticationHelper;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event) {
        $token = $event->getAuthenticationToken();

        if(!$token instanceof UsernamePasswordToken) {
            return;
        }

        $user = $token->getUser();
        if(! $user instanceof User || !$user->getTwoFactorAuthentication()) {
            return;
        }

        $event->getRequest()->getSession()->set($this->authenticationHelper->getSessionKey($token), null);
        $this->authenticationHelper->makeEmailAuthentication($user);
    }
}