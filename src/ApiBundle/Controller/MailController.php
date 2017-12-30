<?php

namespace ApiBundle\Controller;

use ApiBundle\Base\Model\CollectionResponse;
use ApiBundle\Base\Model\ResourceInterface;
use ApiBundle\Base\RestController;
use AppBundle\Entity\Email;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;
use TypeError;
use AppBundle\Mailer\DefaultMailer;

class MailController extends RestController
{
    /**
     * @return string
     */
    public static function getResourceClass(): string
    {
        return Email::class;
    }

    /**
     * Get single Email
     *
     * @Rest\Get(requirements={"mailId": "\d+"})
     * @Rest\View(serializerGroups={RestController::GROUP_OUTPUT})
     *
     * @ApiDoc(
     *     section="Email",
     *     output={
     *          "class" = "AppBundle\Entity\Email",
     *          "groups" = {RestController::GROUP_OUTPUT}
     *     },
     *     responseMap={
     *         404 = "ApiBundle\Base\Exception\NotFountException"
     *     }
     * )
     * @param int $mailId
     * @return Email
     * @throws TypeError
     */
    public function getMailAction(int $mailId): ResourceInterface
    {
        return $this->getRest($mailId);
    }

    /**
     * Get collection of Email
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @Rest\QueryParam(name="page", requirements="\d+", strict=true, default=RestController::PAGE,
     *     description="Pagination page."
     * )
     * @Rest\QueryParam(name="page_limit", requirements="([1-9]|\d{2}|100)", default=RestController::PAGE_LIMIT,
     *     description="Count of collection items per page. Allowed values are from 1 to 100."
     * )
     *
     * @Rest\View(serializerGroups={RestController::GROUP_OUTPUT})
     *
     * @ApiDoc(
     *     section="Email",
     *     output={
     *         "class" = "ApiBundle\Base\Model\CollectionResponse",
     *         "collectionName" = "AppBundle\Entity\Email",
     *         "groups" = {RestController::GROUP_OUTPUT}
     *     }
     * )
     *
     * @param ParamFetcher $paramFetcher
     * @return CollectionResponse
     */
    public function getMailsAction(ParamFetcher $paramFetcher): CollectionResponse
    {
        return $this->cgetRest($paramFetcher);
    }

    /**
     * Create new Email
     *
     * @ParamConverter("mail", converter="fos_rest.request_body")
     * @Rest\Post("mails")
     * @Rest\View(statusCode=201, serializerGroups={RestController::GROUP_PRIMARY_KEY})
     *
     * @ApiDoc(
     *     section="Email",
     *     input={
     *          "class" = "AppBundle\Entity\Email",
     *          "groups" = {RestController::GROUP_INPUT}
     *     },
     *     responseMap={
     *         201 = {
     *              "class" = "AppBundle\Entity\Email",
     *              "groups" = {RestController::GROUP_PRIMARY_KEY},
     *              "parsers" = {"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"}
     *         },
     *         400 = "ApiBundle\Base\Exception\ValidationException"
     *     }
     * )
     * @param Email $mail
     * @return ResourceInterface
     */
    public function postMailAction(Email $mail)
    {
        return $this->postRest($mail);
    }

    /**
     * Send emails in background
     *
     * @Rest\Post("mails/send")
     * @Rest\View(statusCode=200)
     *
     * @Rest\QueryParam(name="limit", requirements="\d+", default=0,
     *     description="Sending limit per action, default there is no limit."
     * )
     * @Rest\QueryParam(name="provider", default=DefaultMailer::PROVIDER,
     *     description="Mailer provider."
     * )
     *
     * @Rest\QueryParam(name="env", default="prod",
     *     description="CLI environment."
     * )
     *
     * @ApiDoc(
     *     section="Email",
     *     responseMap={
     *         200 = "Email sending process working in background"
     *     }
     * )
     *
     * @param ParamFetcher $paramFetcher
     * @return Response
     */
    public function postMailsSendAction(ParamFetcher $paramFetcher)
    {
        $rootDir = $this->get('kernel')->getRootDir();
        $options = '';
        $provider = $paramFetcher->get('provider');
        $limit = $paramFetcher->get('limit');
        $environment = $paramFetcher->get('env');

        if ($provider) {
            $options .= ' --provider=' . $provider;
        }

        if ($limit || $limit === 0) {
            $options .= ' --limit=' . $limit;
        }

        if ($environment) {
            $options .= ' --env=' . $environment;
        }

        $process = new Process($rootDir . '/../bin/console mailer:send' . $options);
        $process->start();

        $message = 'Email sending process working in background';

        return $this->handleView($this->view(['message' => $message], 200));
    }

}