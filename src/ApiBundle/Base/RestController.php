<?php

namespace ApiBundle\Base;

use ApiBundle\Base\Exception\ValidationException;
use ApiBundle\Base\Exception\ConflictException;
use ApiBundle\Base\Exception\NotFoundException;
use ApiBundle\Base\Model\CollectionResponse;
use ApiBundle\Base\Model\ResourceInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\Config\Definition\Exception\Exception;
use TypeError;

abstract class RestController extends FOSRestController
{
    const RESOURCE_QB_ALIAS = 'r';
    const PAGE = 1;
    const PAGE_LIMIT = 100;

    const GROUP_INPUT = 'input';
    const GROUP_OUTPUT = 'output';
    const GROUP_PRIMARY_KEY = 'primary_key';
    const GROUPS = [self::GROUP_INPUT, self::GROUP_OUTPUT, self::GROUP_PRIMARY_KEY];

    /**
     * @return string
     */
    abstract public static function getResourceClass(): string;

    /**
     * Base handling of getting one REST resource (method GET)
     *
     * @param int|array $params
     * @return ResourceInterface
     * @throws TypeError
     * @throws NotFoundException
     */
    protected function getRest($params): ResourceInterface
    {
        $repository = $this->getDoctrine()->getManager()->getRepository(static::getResourceClass());

        if (false === is_array($params) && false === is_int($params)) {
            throw new TypeError(sprintf(
                'Argument %d passed to %s must be of the type %s, %s given',
                2,
                __METHOD__ . '()',
                'int or array',
                gettype($params)
            ));
        } elseif (is_int($params)) {
            $params = ['id' => $params];
        }

        /** @var $resource ResourceInterface */
        $resource = $repository->findOneBy($params);

        if (is_null($resource)) {
            throw new NotFoundException();
        }

        return $resource;
    }

    /**
     * Base handling of getting collection of REST resources (method GET)
     * @param ParamFetcher $paramFetcher
     * @return CollectionResponse
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    protected function cgetRest(ParamFetcher $paramFetcher): CollectionResponse
    {
        $resourceQueryBuilder = $this->createResourceQueryBuilder($paramFetcher);
        $resourceList = $resourceQueryBuilder->getQuery()->getResult();

        $resourceCountQueryBuilder = $this->createResourceCountQueryBuilder($resourceQueryBuilder);
        $totalItems = $resourceCountQueryBuilder->getQuery()->getSingleScalarResult();

        return (new CollectionResponse())
            ->setItems($resourceList)
            ->setTotalItems($totalItems);
    }

    /**
     * @param ParamFetcher $paramFetcher
     * @return QueryBuilder
     */
    private function createResourceQueryBuilder(ParamFetcher $paramFetcher): QueryBuilder
    {
        /** @var $repository EntityRepository */
        $repository = $this->getDoctrine()->getManager()->getRepository(static::getResourceClass());
        $resourceQueryBuilder = $repository->createQueryBuilder(self::RESOURCE_QB_ALIAS);

        //limit
        $limit = $paramFetcher->get('page_limit');
        $resourceQueryBuilder->setMaxResults($limit);

        //offset
        $page = $paramFetcher->get('page');
        if ($page > 1) {
            $resourceQueryBuilder->setFirstResult(($page*$limit)-$limit);
        }

        return $resourceQueryBuilder;
    }

    /**
     * @param QueryBuilder $resourceQueryBuilder
     * @return QueryBuilder
     */
    private function createResourceCountQueryBuilder(QueryBuilder $resourceQueryBuilder): QueryBuilder
    {
        return $resourceQueryBuilder->select($resourceQueryBuilder->expr()->count(self::RESOURCE_QB_ALIAS))
            ->setMaxResults(null)
            ->setFirstResult(null);
    }

    /**
     * Base handling of creating REST resource (method POST)
     * @param ResourceInterface $resource
     * @return ResourceInterface
     * @throws ValidationException
     */
    protected function postRest(ResourceInterface $resource): ResourceInterface
    {
        $this->handleValidationErrors($resource);

        $entityManager = $this->getDoctrine()->getManager();

        try {
            $entityManager->persist($resource);
            $entityManager->flush();
        } catch (Exception $e) {
            throw new ConflictException('Conflict', $e);
        }

        return $resource;
    }

    /**
     * @param ResourceInterface $resource
     * @throws ValidationException
     */
    private function handleValidationErrors(ResourceInterface $resource): void
    {
        $violationList = $this->get('validator')->validate($resource);

        if ($violationList->count() > 0) {
            throw new ValidationException($violationList);
        }
    }

}