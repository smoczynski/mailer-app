<?php
namespace Tests\Api;

use ApiTester;
use Codeception\Util\HttpCode;

class AttachmentControllerCest
{
    public function _before()
    {
        file_put_contents(codecept_data_dir('apiFile.txt'), 'Test API file for upload tests.');
    }

    public function uploadAndRemoveAttachmentFromCache(ApiTester $I)
    {
        $I->wantTo('Upload file to cache and then remove it.');

        $I->sendPOST('/attachments/cache', [], ['file' => codecept_data_dir('apiFile.txt')]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();

        $I->sendDELETE(
            '/attachments/cache',
            ['fileCacheName' => $I->grabDataFromResponseByJsonPath('$.fileCacheName')[0]]
        );
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
    }

    public function saveAndMoveFileToAttachmentDirectory(ApiTester $I)
    {
        $I->wantTo('Move file from cache to attachment directory');

        $I->sendPOST('/attachments/cache', [], ['file' => codecept_data_dir('apiFile.txt')]);

        $attachment = [
            'cacheName' => $I->grabDataFromResponseByJsonPath('$.fileCacheName')[0],
            'name' => 'apiFile.txt',
            'id' => 'file id',
        ];

        $I->sendPUT('/attachments', ['attachments' => [$attachment]]);

        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function movingAttachmentError(ApiTester $I)
    {
        $I->wantTo('Check not found exception during moving file to attachment dir.');

        $I->sendPUT('/attachments', ['attachments' => [['name' => 'wrongFile.txt']]]);
        $I->seeResponseIsJson();
        $I->seeResponseContains('Internal Server Error');
    }
}
