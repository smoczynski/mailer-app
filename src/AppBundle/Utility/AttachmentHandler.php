<?php

namespace AppBundle\Utility;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AttachmentHandler
{
    const FILE_SIZE_LIMIT = 10000000;

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
     * @return bool
     */
    public function removeFileFromCache(string $filename): bool
    {
        $file = $this->attachmentDir . 'cache/' . $filename;

        if (false === is_file($file)) {
            throw new HttpException(403,"File does not exist.");
        }

        unlink($file);

        return true;
    }

    /**
     * @param array $attachments
     */
    public function moveFilesFromCacheToAttachmentDir(array $attachments): void
    {
        foreach ($attachments as $attachment) {
            $filePath = $this->attachmentDir . 'cache/' . $attachment['cacheName'];

            if (false === is_file($filePath)) {
                throw new HttpException(403,"File does not exist.");
            }

            rename($filePath,$this->attachmentDir . $attachment['cacheName']);
        }
    }

    /**
     * @param UploadedFile $file
     */
    private function validateFile(UploadedFile $file): void
    {
        if (self::FILE_SIZE_LIMIT < $file->getClientSize()) {
            throw new HttpException(403,"Uploaded file is to big. Max size is 10MB");
        }
    }
}