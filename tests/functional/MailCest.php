<?php
namespace Tests\Functional;

use AppBundle\Entity\Email;
use FunctionalTester;

class MailCest
{
    public function checkEmailRepository(FunctionalTester $I)
    {
        $mail = new Email();
        $mail->setTitle('Custom title');
        $mail->setSender('sender@example.com');
        $mail->setRecipients(['recipient@example.com']);
        $mail->setContent('Test content');
        $mail->setStatus('pending');
        $mail->setPriority(1);

        $I->persistEntity($mail);

        $I->seeInRepository(Email::class, array('title' => 'Custom title'));
    }
}