<?php
namespace Tests\AppBundle\Utility;

use AppBundle\Utility\AttachmentHandler;
use Codeception\Test\Unit;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use UnitTester;

class AttachmentHandlerTest extends Unit
{
    /** @var UnitTester */
    protected $tester;

    /** @var AttachmentHandler */
    private $attachmentHandler;

    public function _before()
    {
        $this->attachmentHandler = $this->tester->grabService('AppBundle\Utility\AttachmentHandler');
    }

    /**
     * check upload and remove of file
     */
    public function testUploadAndRemoveFile()
    {
        $file = new UploadedFile(
            $this->createTestFile(),
            'testFile',
            'text/text',
            null,
            null,
            true
        );

        $filename = $this->attachmentHandler->uploadFileToCacheDir($file);
        $this->tester->assertNotEmpty($filename);
        $this->tester->assertTrue($this->attachmentHandler->removeFileFromCache($filename));
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testRemoveFileNotValid()
    {
        $this->attachmentHandler->removeFileFromCache('notExistingFile.txt');
    }

    /**
     * @return string
     */
    private function createTestFile(): string
    {
        $filePath = codecept_data_dir('testFile.txt');
        file_put_contents($filePath, 'custom data');

        return $filePath;
    }
}