<?php

namespace AppBundle\Manager;


use AppBundle\Entity\Post;
use AppBundle\Repository\PostRepository;
use Doctrine\ORM\EntityRepository;

class PostManager extends AbstractManager
{
    /**
     * @return PostRepository
     */
    public function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(Post::class);
    }
}