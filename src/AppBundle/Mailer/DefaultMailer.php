<?php

namespace AppBundle\Mailer;

use ApiBundle\Controller\AttachmentController;
use AppBundle\Entity\Email;
use Exception;
use Swift_Attachment;
use Swift_Mailer;
use Swift_Message;

class DefaultMailer extends AbstractMailer implements MailerInterface
{
    const PROVIDER = 'default';
    private $mailer;
    private $projectDir;

    /**
     * DefaultMailer constructor.
     * @param string $projectDir
     * @param Swift_Mailer $mailer
     */
    public function __construct(string $projectDir, Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
        $this->projectDir = $projectDir;
    }

    public function send(Email $email): string
    {
        $attachmentDir = $this->projectDir . AttachmentController::REL_PATH_ATTACHMENT;

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