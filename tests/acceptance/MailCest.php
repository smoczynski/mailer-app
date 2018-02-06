<?php
namespace Tests\Acceptance;

use AcceptanceTester;
use Helper\Acceptance;

class MailCest
{
    /** @var Acceptance */
    private $helper;
    private $keyboard;

    public function _inject(Acceptance $helper)
    {
        $this->helper = $helper;
    }

    public function _before()
    {
        $this->keyboard = $this->helper->getKeyboard();
    }

    public function createEmailWithTwoRecipients(AcceptanceTester $I)
    {
        file_put_contents(codecept_data_dir('apiFile.txt'), 'Test API file for upload tests.');
        $emailTitle = 'Unique email title ' . uniqid();

        $I->wantTo('Create email for two recipients');
        $I->amOnPage('/');
        $I->see('Email management');
        $I->click('Create email');
        $I->seeInCurrentUrl('/email/add');
        $I->see('Defining an email');
        $I->fillField('sender', 'sender@example.com');
        $I->click('Add recipient');
        $I->click('Add recipient');
        $I->fillField('recipients[]', 'first@example.com');
        $I->clickWithLeftButton("(//*[contains(@name, 'recipients[]')])[2]");
        $this->keyboard->sendKeys('second@example.com');
        $I->fillField('title',$emailTitle);
        $I->clickWithLeftButton("(//*[contains(@class, 'note-editable')])");
        $this->keyboard->sendKeys('Text fo summernote text editor field.');
        $I->selectOption('priority','Medium');
        $I->selectOption('status','pending');
        $I->attachFile('file', 'testFile.txt');
        $I->wait(0.5);
        $I->click('Create email');
        $I->wait(0.5);
        $I->see('Email created');
        $I->canSeeInDatabase('email', ['title' => $emailTitle]);
    }

    public function createEmailValidationCheck(AcceptanceTester $I)
    {
        $I->wantTo('Ensure that validation works properly.');
        $I->amOnPage('/email/add');
        $I->click('Create email');
        $I->wait(0.5);
        $I->see('title - This value should not be blank');
        $I->fillField('title', 'Test title');
        $I->click('Create email');
        $I->wait(0.5);
        $I->dontSee('title - This value should not be blank');
        $I->see('sender - This value should not be blank');
        $I->fillField('sender', 'sender@example.com');
        $I->click('Create email');
        $I->wait(0.5);
        $I->dontSee('sender - This value should not be blank');
        $I->see('recipients - This value should not be blank');
        $I->click('Add recipient');
        $I->fillField('recipients[]', 'first@example.com');
        $I->click('Create email');
        $I->wait(0.5);
        $I->dontSee('recipients - This value should not be blank');
        $I->see('content - This value should not be blank');
        $I->clickWithLeftButton("(//*[contains(@class, 'note-editable')])");
        $this->keyboard->sendKeys('Text fo summernote text editor field.');
        $I->click('Create email');
        $I->wait(0.5);
        $I->see('Email created');
    }
}
