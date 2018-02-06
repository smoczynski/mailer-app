<?php
namespace Tests\AppBundle\Mailer;

use AppBundle\Mailer\MailerController;
use Codeception\Test\Unit;
use UnitTester;

class MailerControllerTest extends Unit
{
    /** @var UnitTester */
    protected $tester;

    /**
     *
     */
    public function testSendingEmails()
    {
        /** @var MailerController $mailerController */
        $mailerController = $this->tester->grabService('AppBundle\Mailer\MailerController');
        $result = $mailerController->handleSendingEmails();

        $this->tester->assertEquals(0, $result['sentEmailsCount']);
        $this->tester->assertEquals(0, $result['brokenEmailsCount']);
    }

    /**
     * @expectedException \Exception
     */
    public function testWrongProvider()
    {
        /** @var MailerController $mailerController */
        $mailerController = $this->tester->grabService('AppBundle\Mailer\MailerController');
        $mailerController->handleSendingEmails('wrongProvider');
    }
}