<?php
namespace Tests\AppBundle\Mailer;

use AppBundle\Entity\Email;
use AppBundle\Mailer\DefaultMailer;
use Codeception\Test\Unit;

class DefaultMailerTest extends Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function provideValid()
    {
        return [
            [
                $this->createEmail()
                    ->setTitle('Custom title')
                    ->setSender('sender@example.com')
                    ->setRecipients(['recipient@example.com'])
                    ->setContent('Custom content')
            ],
            [
                $this->createEmail()
                    ->setTitle('Different title')
                    ->setSender('author@example.com')
                    ->setRecipients(['other@example.com'])
                    ->setContent('Custom text')
            ],
        ];
    }

    /**
     * @dataProvider provideValid
     * @param Email $email
     */
    public function testSendingEmailValid(Email $email)
    {
        $mailer = $this->tester->grabService('test.AppBundle\Mailer\DefaultMailer');
        $this->tester->assertContains(DefaultMailer::SENDING_SUCCESS, $mailer->send($email));
    }

    public function provideNotValid()
    {
        return [
            [
                $this->createEmail()
            ],
            [
                $this->createEmail()
                    ->setRecipients([])
                    ->setAttachments(null)
            ],
        ];
    }

    /**
     * @dataProvider provideNotValid
     * @param Email $email
     */
    public function testSendingEmailNotValid(Email $email)
    {
        $mailer = $this->tester->grabService('test.AppBundle\Mailer\DefaultMailer');
        $this->tester->assertContains(DefaultMailer::SENDING_FAILED, $mailer->send($email));
    }

    /**
     * @return Email
     */
    private function createEmail(): Email
    {
        return (new Email())
            ->setPriority(1)
            ->setStatus('pending')
            ->setAttachments([]);
    }
}