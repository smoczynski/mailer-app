<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class AttachmentController extends FOSRestController
{
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
        $filename = $this->get('AppBundle\Utility\AttachmentHandler')->uploadFileToCacheDir($file);

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
        $filename = $request->request->get('fileCacheName');
        $this->get('AppBundle\Utility\AttachmentHandler')->removeFileFromCache($filename);

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
        $this->get('AppBundle\Utility\AttachmentHandler')->moveFilesFromCacheToAttachmentDir($attachments);

        return $this->handleView($this->view(['message' => 'Files saved and removed from cache.'], 200));
    }
}