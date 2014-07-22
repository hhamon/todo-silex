<?php

namespace Todo\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class AbstractController
{
    protected function createHttpException($statusCode, $message = '', array $headers = array())
    {
        return new HttpException($statusCode, $message, null, $headers);
    }

    protected function createNotFoundException($message = '')
    {
        return $this->createHttpException(404, $message);
    }

    protected function redirect($url, $statusCode = 302)
    {
        return new RedirectResponse($url, $statusCode);
    }
}
