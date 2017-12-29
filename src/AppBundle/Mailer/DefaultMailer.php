<?php

namespace AppBundle\Mailer;

use AppBundle\Entity\Email;
use Swift_Mailer;
use Swift_Message;

class DefaultMailer extends AbstractMailer implements MailerInterface
{
    const PROVIDER = 'default';
    private $mailer;

    /**
     * DefaultMailer constructor.
     * @param Swift_Mailer $mailer
     */
    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(Email $email): string
    {
        try {
            $message = (new Swift_Message())
                ->setSubject($email->getTitle())
                ->setFrom($email->getSender())
                ->setTo($email->getRecipients())
                ->setBody($email->getContent(), 'text/html');
            $this->mailer->send($message);
        } catch (\Exception $e) {
            return self::SENDING_FAILED;
        }

        return self::SENDING_SUCCESS;
    }

}