<?php

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

abstract class AbstractManager
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param $entity
     * @return bool
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create($entity): bool
    {
        //TODO Rewrite on php7.2 (object type hinting)
        if (!is_object($entity)) {
            return false;
        }

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @param $entity
     * @return bool
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove($entity): bool
    {
        //TODO Rewrite on php7.2 (object type hinting)
        if (!is_object($entity)) {
            return false;
        }

        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        return true;
    }

    abstract public function getRepository(): EntityRepository;
}