<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Yves\Application\Plugin\Provider\ExceptionService;

use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;

class DefaultExceptionHandler implements ExceptionHandlerInterface
{

    /**
     * @param \Symfony\Component\Debug\Exception\FlattenException $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleException(FlattenException $exception)
    {
        return new Response($exception->getMessage());
    }

}
