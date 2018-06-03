<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class CategoryRepository extends EntityRepository
{
    public function getQueryBuilder()
    {
        $queryBuilder = $this->createQueryBuilder('c');

        return $queryBuilder;
    }
}
