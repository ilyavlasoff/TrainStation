<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use PhpParser\Node\Expr\Cast\Object_;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class EmailAuthenticatorHelper
{
    /**
     * @var EntityManager $em
     */
    private $em;

    /**
     * @var Object $mailer
     */
    private $mailer;

    public function __construct(EntityManager $em, MailerInterface $mailer)
    {
        $this->em = $em;
        $this->mailer = $mailer;
    }

    public function makeEmailAuthentication(User $user) {
        $code = $this->generateCode($user);
        $this->sendCode($user, $code);
    }

    private function generateCode(User $user) : string {
        $code = mt_rand(1000, 9999);
        $user->setTwoFactorCode($code);
        $this->em->persist($user);
        $this->em->flush();
        return strval($code);
    }

    private function sendCode(User $user, string $code): void {
        $email = new Email();
        $email->from()
            ->to($user->getEmail())
            ->subject('Login confirmation')
            ->html("<p>Your confirmation code: $code</p>");
        $this->mailer->send($email);
    }

    public function checkCode(User $user, string $code): bool {
        return $user->getTwoFactorCode() == $code;
    }

    public function getSessionKey(TokenInterface $token): string {
        return sprintf('two_factor_%s', $token->getUsername());
    }

}