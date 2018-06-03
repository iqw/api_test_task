<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository
{
    public function getQueryBuilder()
    {
        $queryBuilder = $this->createQueryBuilder('p');

        return $queryBuilder;
    }
}
