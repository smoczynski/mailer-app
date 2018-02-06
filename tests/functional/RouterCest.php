<?php
namespace Tests\Functional;

use Codeception\Example;
use FunctionalTester;

class RouterCest
{
    /**
     * @dataProvider pageProvider
     * @param FunctionalTester $I
     * @param Example $example
     */
    public function appRouteValidation(FunctionalTester $I, Example $example)
    {
        $I->amOnPage($example['page']);
        $I->click($example['button']);
        $I->see($example['text']);

    }

    /**
     * @return array
     */
    private function pageProvider()
    {
        return [
            [
                'page' => '/',
                'button' => 'Create email',
                'text' => 'Defining an email',
            ],
            [
                'page' => '/',
                'button' => 'API Documentation',
                'text' => 'API documentation',
            ],
            [
                'page' => '/',
                'button' => 'API endpoint listing emails',
                'text' => 'items',
            ],
            [
                'page' => '/email/add',
                'button' => 'Go back',
                'text' => 'Email management',
            ],

        ];
    }
}
