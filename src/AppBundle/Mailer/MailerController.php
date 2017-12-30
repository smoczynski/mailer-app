<?php

namespace AppBundle\Mailer;

use AppBundle\Entity\Email;
use DateTime;
use Doctrine\ORM\EntityManager;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class MailerController
{
    private $mailerLocator;
    private $em;

    /**
     * MailerController constructor.
     * @param ContainerInterface $mailerLocator
     * @param EntityManager $em
     */
    public function __construct(ContainerInterface $mailerLocator, EntityManager $em)
    {
        $this->mailerLocator = $mailerLocator;
        $this->em = $em;
    }

    /**
     * @param string $provider
     * @param int|null $limit
     * @return array
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     */
    public function handleSendingEmails(string $provider = 'default', int $limit = 0): array
    {
        /** @var MailerInterface $mailer */
        $mailer = $this->getMatchingMailer($provider);
        $emails = $this->em->getRepository(Email::class)->findAllPendingEmails($limit);
        $sentEmailsCount = 0;
        $brokenEmailsCount = 0;

        array_walk($emails, function (Email $email) use ($mailer, &$sentEmailsCount, &$brokenEmailsCount) {
            $status = $mailer->send($email);

            switch ($status) {
                case AbstractMailer::SENDING_SUCCESS:
                    $email->setStatus(Email::STATUS_SENT);
                    $email->setSentAt(new DateTime('now'));
                    $sentEmailsCount++;
                    break;
                case AbstractMailer::SENDING_FAILED:
                    $email->setStatus(Email::STATUS_BROKEN);
                    $brokenEmailsCount++;
                    break;
            }
        });

        $this->em->flush();

        return [
            'sentEmailsCount' => $sentEmailsCount,
            'brokenEmailsCount' => $brokenEmailsCount,
        ];
    }

    /**
     * @param string $provider
     * @return MailerInterface
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function getMatchingMailer(string $provider): MailerInterface
    {
        if (false === $this->mailerLocator->has($provider)) {
            throw new Exception('Mailer ' . $provider . ' does not exist.');
        }

        return $this->mailerLocator->get($provider);
    }

}