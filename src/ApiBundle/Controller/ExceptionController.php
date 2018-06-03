<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Rest\RouteResource("exception", pluralize=false)
 */
class ExceptionController extends FOSRestController implements ClassResourceInterface
{
    use ResponseFormatterTrait;

    /**
     * @param \Exception $exception
     * @return Response
     */
    public function showAction($exception)
    {
        $view = $this->view($this->createError($exception->getMessage()));

        return $this->handleView($view);
    }
}