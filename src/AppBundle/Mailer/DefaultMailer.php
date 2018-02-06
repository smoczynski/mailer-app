<?php

namespace AppBundle\Mailer;

use AppBundle\Entity\Email;
use Exception;
use Swift_Attachment;
use Swift_Mailer;
use Swift_Message;

class DefaultMailer extends AbstractMailer implements MailerInterface
{
    const PROVIDER = 'default';
    private $mailer;
    private $attachmentDir;

    /**
     * DefaultMailer constructor.
     * @param string $attachmentDir
     * @param Swift_Mailer $mailer
     */
    public function __construct(string $attachmentDir, Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
        $this->attachmentDir = $attachmentDir;
    }

    public function send(Email $email): string
    {
        $attachmentDir = $this->attachmentDir;

        try {
            $message = (new Swift_Message())
                ->setSubject($email->getTitle())
                ->setFrom($email->getSender())
                ->setBody($email->getContent(), 'text/html');

            foreach ($email->getRecipients() as $recipient) {
                $message->addTo($recipient);
            }

            foreach ($email->getAttachments() as $attachment) {
                $message->attach(
                    Swift_Attachment::fromPath($attachmentDir . $attachment['cacheName'])
                        ->setFilename($attachment['name'])
                );
            }

            $this->mailer->send($message);
        } catch (Exception $e) {
            return self::SENDING_FAILED;
        }

        return self::SENDING_SUCCESS;
    }

}