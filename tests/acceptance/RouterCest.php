<?php
namespace Tests\Acceptance;

use AcceptanceTester;
use Codeception\Example;

class RouterCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage('/');
    }

    /**
     * @dataprovider pageProvider
     * @param AcceptanceTester $I
     * @param Example $example
     */
    public function appRouteValidation(AcceptanceTester $I, Example $example)
    {
        $I->wantTo('Check that routes works properly.');
        $I->click($example['button']);
        $I->wait($example['wait']);
        $I->see($example['text']);
    }

    private function pageProvider()
    {
        return [
            [
                'button' => 'Create email',
                'text' => 'Defining an email',
                'wait' => 0
            ],
            [
                'button' => 'API Documentation',
                'text' => 'API documentation',
                'wait' => 0
            ],
            [
                'button' => 'API endpoint listing emails',
                'text' => 'items',
                'wait' => 0
            ],
            [
                'button' => 'API endpoint - run background process sending emails',
                'text' => 'Email sending process working in background',
                'wait' => 0.5
            ],
        ];
    }
}