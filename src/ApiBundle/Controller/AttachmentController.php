<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations as Rest;

class AttachmentController extends FOSRestController
{
    const REL_PATH_ATTACHMENT_CACHE = '/var/attachment/cache/';
    const REL_PATH_ATTACHMENT = '/var/attachment/';

    /**
     * Send attachment to cache directory
     *
     * @Rest\Post("attachment/cache")
     * @Rest\View(statusCode=200)
     *
     * @ApiDoc(
     *     section="Attachment",
     *     responseMap={
     *         200 = "Attachment sent to cache directory"
     *     }
     * )
     *
     * @param Request $request
     * @return Response
     */
    public function postAttachmentCacheAction(Request $request)
    {
        $file = $request->files->get('file');

        if (false === $file) {
            throw new HttpException(403,"There is no file attached.");
        }

        if (2000000 < $file->getClientSize()) {
            throw new HttpException(403,"Uploaded file is to big. Max size is 2MB");
        }

        if (false === in_array($file->guessExtension(), ['jpeg', 'gif','jpg','png'])) {
            throw new HttpException(403,"Incorrect file format.");
        }

        $projectDir = $this->get('kernel')->getProjectDir();
        $cacheDir = $projectDir . self::REL_PATH_ATTACHMENT_CACHE;
        $filename = md5(uniqid());
        $file->move($cacheDir, $filename);

        return $this->handleView($this->view(['fileCacheName' => $filename], 200));
    }

    /**
     * Delete attachment from cache directory
     *
     * @Rest\Delete("attachment/cache")
     * @Rest\View(statusCode=200)
     *
     * @ApiDoc(
     *     section="Attachment",
     *     responseMap={
     *         200 = "Attachment removed from cache directory"
     *     }
     * )
     *
     * @param Request $request
     * @return Response
     */
    public function deleteAttachmentCacheAction(Request $request)
    {
        $fileCacheName = $request->request->get('fileCacheName');
        $file = $this->get('kernel')->getProjectDir() . self::REL_PATH_ATTACHMENT_CACHE . $fileCacheName;

        if (false === is_file($file)) {
            throw new HttpException(403,"File does not exist.");
        }

        unlink($file);

        return $this->handleView($this->view(['message' => 'Cached file deleted.'], 200));
    }

    /**
     * Move attachment from cache to attachment directory
     *
     * @Rest\Put("attachment")
     * @Rest\View(statusCode=200)
     *
     * @ApiDoc(
     *     section="Attachment",
     *     responseMap={
     *         200 = "Attachment moved from cache to attachment directory"
     *     }
     * )
     *
     * @param Request $request
     * @return Response
     */
    public function putAttachmentAction(Request $request)
    {
        $attachments = $request->request->get('attachments');
        $projectDir = $this->get('kernel')->getProjectDir();
        $cacheDir = $projectDir . self::REL_PATH_ATTACHMENT_CACHE;
        $attachmentDir = $projectDir . self::REL_PATH_ATTACHMENT;

        foreach ($attachments as $attachment) {
            rename($cacheDir . $attachment['cacheName'], $attachmentDir . $attachment['cacheName']);
        }

        return $this->handleView($this->view(['message' => 'Files saved and removed from cache.'], 200));
    }
}