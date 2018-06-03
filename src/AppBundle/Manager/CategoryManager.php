<?php

namespace AppBundle\Manager;


use AppBundle\Entity\Category;
use AppBundle\Repository\CategoryRepository;
use Doctrine\ORM\EntityRepository;

class CategoryManager extends AbstractManager
{
    /**
     * @return CategoryRepository
     */
    public function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(Category::class);
    }
}