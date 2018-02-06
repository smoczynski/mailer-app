<?php
namespace Tests\Api;

use ApiTester;
use AppBundle\Entity\Email;
use Codeception\Util\HttpCode;

class MailerControllerCest
{
    public function createEmail(ApiTester $I)
    {
        $data = [
            'title' => 'Custom title',
            'sender' => 'sender@example.com',
            'recipients' => ['recipient@example.com'],
            'content' => '<p>My content</p>',
            'priority' => 1,
            'status' => 'pending',
        ];

        $I->wantTo('Create new email and modify');
        $I->haveHttpHeader('Content-type', 'application/json');
        $I->sendPOST('/mails', $data);
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"id":1}');
    }

    public function getEmailList(ApiTester $I)
    {
        $I->wantTo('Get email list from API');
        $I->sendGET('/mails');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
    }

    public function getSingleEmail(ApiTester $I)
    {
        $email = (new Email())
            ->setTitle('Custom title')
            ->setSender('sender@example.com')
            ->setRecipients(['recipient@example.com'])
            ->setContent('<p>My content</p>')
            ->setPriority(1)
            ->setStatus('pending');

        $I->persistEntity($email);
        $I->seeInRepository(Email::class, ['title' => 'Custom title']);

        $I->wantTo('Get single email list from API');
        $I->sendGET('/mails/1');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
    }

    public function sendEmails(ApiTester $I)
    {
        $email = (new Email())
            ->setTitle('Custom title')
            ->setSender('sender@example.com')
            ->setRecipients(['recipient@example.com'])
            ->setContent('<p>My content</p>')
            ->setPriority(1)
            ->setStatus('pending');

        $I->persistEntity($email);
        $I->seeInRepository(Email::class, ['title' => 'Custom title']);

        $I->wantTo('Run process sending emails');
        $I->sendPost('/mails/send?env=test');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
    }
}
