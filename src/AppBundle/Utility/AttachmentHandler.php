<?php

namespace AppBundle\Utility;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AttachmentHandler
{
    private $attachmentDir;

    /**
     * AttachmentHandler constructor.
     * @param string $attachmentDir
     */
    public function __construct(string $attachmentDir)
    {
        $this->attachmentDir = $attachmentDir;
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function uploadFileToCacheDir(UploadedFile $file): string
    {
        $this->validateFile($file);

        $filename = md5(uniqid());
        $file->move($this->attachmentDir . 'cache/', $filename);

        return $filename;
    }

    /**
     * @param string $filename
     */
    public function removeFileFromCache(string $filename): void
    {
        $file = $this->attachmentDir . 'cache/' . $filename;

        if (false === is_file($file)) {
            throw new HttpException(403,"File does not exist.");
        }

        unlink($file);
    }

    /**
     * @param array $attachments
     */
    public function moveFilesFromCacheToAttachmentDir(array $attachments): void
    {
        foreach ($attachments as $attachment) {
            rename(
                $this->attachmentDir . 'cache/' . $attachment['cacheName'],
                $this->attachmentDir . $attachment['cacheName']
            );
        }
    }

    /**
     * @param UploadedFile $file
     */
    private function validateFile(UploadedFile $file): void
    {
        if (false === $file) {
            throw new HttpException(403,"There is no file attached.");
        }

        if (10000000 < $file->getClientSize()) {
            throw new HttpException(403,"Uploaded file is to big. Max size is 10MB");
        }
    }

}