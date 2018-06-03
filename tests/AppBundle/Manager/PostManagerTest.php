<?php

namespace Tests\AppBundle\Manager;

use AppBundle\Entity\Post;
use AppBundle\Manager\PostManager;
use AppBundle\Repository\PostRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use PHPUnit\Framework\TestCase;

class PostManagerTest extends TestCase
{
    protected function createEntityManagerMock($fail = false)
    {
        $entityManager = $this->createMock(EntityManager::class);

        $entityManager->method('persist')
            ->with($this->objectHasAttribute('id'));
        $entityManager->method('remove')
            ->with($this->objectHasAttribute('id'));

        $entityManager->method('flush')
            ->will($this->returnCallback(function () use ($fail) {
                if ($fail) {
                    throw new OptimisticLockException("Lock", new \StdClass);
                }
            }));

        $entityManager->method('getRepository')
            ->with($this->stringContains('AppBundle\Entity\Post'))
            ->will($this->returnCallback(function () use ($fail) {
                if ($fail) {
                    return $this->createMock(EntityRepository::class);
                }
                
                return $this->createMock(PostRepository::class);
            }));

        return $entityManager;
    }

    /**
     * @dataProvider getPositiveData
     */
    public function testCreatePositive($entity, $result)
    {
        $entityManagerMock = $this->createEntityManagerMock();

        if ($result) {
            $entityManagerMock->expects($this->once())
                ->method('persist');
            $entityManagerMock->expects($this->once())
                ->method('flush');
        }

        $manager = new PostManager($entityManagerMock);

        $this->assertEquals($manager->create($entity), $result);
    }

    /**
     * @dataProvider getNegativeData
     */
    public function testCreateNegative($entity, $exception)
    {
        $entityManagerMock = $this->createEntityManagerMock(true);

        $entityManagerMock->expects($this->once())
            ->method('persist');
        $entityManagerMock->expects($this->once())
            ->method('flush');


        $manager = new PostManager($entityManagerMock);

        $this->expectException($exception);

        $manager->create($entity);
    }

    /**
     * @dataProvider getPositiveData
     */
    public function testRemovePositive($entity, $result)
    {
        $entityManagerMock = $this->createEntityManagerMock();

        if ($result) {
            $entityManagerMock->expects($this->once())
                ->method('remove');
            $entityManagerMock->expects($this->once())
                ->method('flush');
        }

        $manager = new PostManager($entityManagerMock);

        $this->assertEquals($manager->remove($entity), $result);
    }

    /**
     * @dataProvider getNegativeData
     */
    public function testRemoveNegative($entity, $exception)
    {
        $entityManagerMock = $this->createEntityManagerMock(true);

        $entityManagerMock->expects($this->once())
            ->method('remove');
        $entityManagerMock->expects($this->once())
            ->method('flush');


        $manager = new PostManager($entityManagerMock);

        $this->expectException($exception);

        $manager->remove($entity);
    }

    public function testGetRepositoryPositive()
    {
        $manager = new PostManager($this->createEntityManagerMock());

        $this->assertInstanceOf(PostRepository::class, $manager->getRepository());
    }

    public function testGetRepositoryNegative()
    {
        $manager = new PostManager($this->createEntityManagerMock(true));

        $this->assertNotInstanceOf(PostRepository::class, $manager->getRepository());
    }

    public function getPositiveData()
    {
        $entity1 = new \StdClass;
        $entity1->id = 0;

        $entity2 = new Post();

        return [
            [$entity1, true],
            [$entity2, true],
            ['test', false],
            [111, false],
            [111.11, false],
        ];
    }

    public function getNegativeData()
    {
        $entity1 = new \StdClass;
        $entity1->id = 0;

        $entity2 = new Post();

        return [
            [$entity1, OptimisticLockException::class],
            [$entity2, OptimisticLockException::class],
        ];
    }
}