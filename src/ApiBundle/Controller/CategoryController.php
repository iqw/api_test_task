<?php

namespace ApiBundle\Controller;

use AppBundle\Manager\CategoryManager;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Rest\RouteResource("category", pluralize=false)
 */
class CategoryController extends FOSRestController implements ClassResourceInterface
{
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

        $view->getContext()->addGroup('category_list');
        $view->getContext()->addGroup('pagination');

        return $this->handleView($view)->setMaxAge($this->getParameter('http_cache_ttl'));
    }

    /**
     * @return CategoryManager
     */
    protected function getManager()
    {
        return $this->get('app.manager.category');
    }
}