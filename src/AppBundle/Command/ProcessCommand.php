<?php

namespace AppBundle\Command;

use AppBundle\Entity\Mail;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\LockHandler;

class ProcessCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mailer:send')
            ->setDescription('Sending all unsend emails')
            ->setHelp('This command runs unless send all email form db with status "unsend"');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lock = new LockHandler('mailer:send');

        if (!$lock->lock()) {
            $output->writeln('The command is already running in another process.');

            return 0;
        }

        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $emails = $em->getRepository(Mail::class)->findAllPendingEmails();

        foreach ($emails as $email) {
            var_dump($email->getId());

        }

        die;

        $i = 0;

        while ($i < 300000) {
            $output->writeln('siema' . $i);
            $i++;
        }

    }

}