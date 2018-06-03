<?php

namespace ApiBundle\Controller;

use AppBundle\Form\ErrorsExtractorTrait;
use AppBundle\Form\Type\PostType;
use AppBundle\Entity\Post;
use AppBundle\Manager\PostManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Rest\RouteResource("post", pluralize=false)
 */
class PostController extends FOSRestController implements ClassResourceInterface
{
    use ErrorsExtractorTrait;
    use ResponseFormatterTrait;

    const RECORDS_PER_PAGE = 10;

    /**
     * @param Request $request
     * @return Response
     */
    public function cgetAction(Request $request)
    {
        $categoryQueryBuilder = $this->getManager()
            ->getRepository()
            ->getQueryBuilder();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($categoryQueryBuilder, (int)$request->get('page', 1), self::RECORDS_PER_PAGE);

        $view = $this->view($this->createSuccess($pagination));

        $view->getContext()->addGroup('post_list');
        $view->getContext()->addGroup('pagination');

        return $this->handleView($view);
    }

    /**
     * @param Post $post
     * @return Response
     */
    public function getAction(Post $post)
    {
        $view = $this->view($this->createSuccess($post));

        $view->getContext()->addGroup('post_details');

        return $this->handleView($view);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function postAction(Request $request)
    {
        return $this->createPost($request, null, true);
    }

    /**
     * @param Request $request
     * @param Post $post
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function putAction(Request $request, Post $post)
    {
        return $this->createPost($request, $post, true);
    }

    /**
     * @param Request $request
     * @param Post $post
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function patchAction(Request $request, Post $post)
    {
        return $this->createPost($request, $post, false);

    }

    /**
     * @param Request $request
     * @param Post|null $post
     * @param bool $clearMissing
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    protected function createPost(Request $request, Post $post = null, $clearMissing = false)
    {
        $form = $this->createForm(PostType::class, $post);
        $form->submit($request->request->get($form->getName()), $clearMissing);

        if (!$form->isValid()) {
            $errors = $this->extractAllErrors($form);
            return $this->handleView($this->view($this->createErrors($errors), 400));
        }

        /** @var Post $post */
        $post = $form->getData();

        $this->getManager()->create($post);

        $view = $this->view($this->createSuccess($post));
        $view->getContext()->addGroup('post_details');

        return $this->handleView($view);
    }

    public function deleteAction(Post $post)
    {
        try {
            $this->getManager()->remove($post);
        } catch (\Exception $exception) {
            return $this->handleView($this->view($this->createError('Internal Server Error'), 500));
        }

        return $this->handleView($this->view($this->createSuccess()));
    }

    /**
     * @return PostManager
     */
    protected function getManager()
    {
        return $this->get('app.manager.post');
    }
}