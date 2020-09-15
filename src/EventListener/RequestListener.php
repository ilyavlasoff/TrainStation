<?php

namespace App\EventListener;

use App\Service\EmailAuthenticatorHelper;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class RequestListener
{
    private $emailAuthenticationHelper;
    private $authorizationChecker;

    public function __construct(
        EmailAuthenticatorHelper $emailAuthenticatorHelper,
        AuthorizationCheckerInterface $authorizationChecker
)
    {
        $this->emailAuthenticationHelper = $emailAuthenticatorHelper;
        $this->authorizationChecker = $authorizationChecker;

    }

    public function onCoreRequest(RequestEvent $event) {

    }
}