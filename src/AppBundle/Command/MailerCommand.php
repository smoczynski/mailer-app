<?php

namespace AppBundle\Command;

use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\LockHandler;

class MailerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mailer:send')
            ->setDescription('Sending all unsent emails')
            ->setHelp('This command send all email with "pending" status')
            ->addOption(
                'limit',
                null,
                InputOption::VALUE_OPTIONAL,
                "You can specify limit of sending emails, default there is no limit",
                null
            )
            ->addOption(
                'provider',
                null,
                InputOption::VALUE_OPTIONAL,
                "You can specify email provider which will be used",
                'default'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws ContainerExceptionInterface
     * @throws Exception
     * @throws NotFoundExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lock = new LockHandler('mailer:send');

        if (!$lock->lock()) {
            $output->writeln('The command is already running in another process.');

            return;
        }

        $limit = $input->getOption('limit');
        $provider = $input->getOption('provider');

        $result = $this
            ->getContainer()
            ->get('AppBundle\Mailer\MailerController')
            ->handleSendingEmails($provider, $limit);

        $output
            ->writeln(
                $result['sentEmailsCount'] . ' emails was sent and ' .
                $result['brokenEmailsCount'] . ' was broken.');
    }
}