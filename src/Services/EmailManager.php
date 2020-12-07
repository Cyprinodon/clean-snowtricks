<?php


namespace App\Services;


use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\CsrfToken;

class EmailManager
{
    private $mailer;
    private $siteAddress;

    public function __construct(MailerInterface $mailer, string $siteAddress)
    {
        $this->mailer = $mailer;
        $this->siteAddress = $siteAddress;
    }

    public function sendPasswordReset(string $to, CsrfToken $token, UserInterface $user)
    {
        $email = (new TemplatedEmail())
            ->from($this->siteAddress)
            ->to(new Address($to))
            ->subject('Votre demande de réinitialisation de mot de passe')
            ->htmlTemplate('emails/passwordReset.html.twig')
            ->context([
                'token' => $token,
                'username' => $user->getUsername(),
            ]);


        $this->mailer->send($email);
    }
}