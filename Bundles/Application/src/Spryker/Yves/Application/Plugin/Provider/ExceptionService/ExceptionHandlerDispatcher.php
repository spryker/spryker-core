<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Yves\Application\Plugin\Provider\ExceptionService;

use Spryker\Yves\Application\Plugin\Exception\UndefinedExceptionHandlerException;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;

class ExceptionHandlerDispatcher
{

    /**
     * @var ExceptionHandlerInterface[]
     */
    protected $exceptionHandlers;

    /**
     * @param ExceptionHandlerInterface[] $exceptionHandlers
     */
    public function __construct(array $exceptionHandlers)
    {
        $this->exceptionHandlers = $exceptionHandlers;
    }

    /**
     * @param \Symfony\Component\Debug\Exception\FlattenException $exception
     *
     * @throws \Spryker\Yves\Application\Plugin\Exception\UndefinedExceptionHandlerException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function dispatch(FlattenException $exception)
    {
        $statusCode = $exception->getStatusCode();

        if (isset($this->exceptionHandlers[$statusCode])) {
            return $this->exceptionHandlers[$statusCode]->handleException($exception);
        }

        throw new UndefinedExceptionHandlerException(sprintf(
            'Undefined exception handler for status code "%d".',
            $statusCode
        ));
    }

}
