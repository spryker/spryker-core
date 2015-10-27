<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerEngine\Yves\Application\Communication\Plugin\ServiceProvider\ExceptionService;

use SprykerEngine\Yves\Application\Communication\Plugin\Exception\UndefinedExceptionHandlerException;
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
     * @param FlattenException $exception
     *
     * @throws UndefinedExceptionHandlerException
     *
     * @return Response
     */
    public function dispatch(FlattenException $exception)
    {
        $statusCode = $exception->getStatusCode();

        if (array_key_exists($statusCode, $this->exceptionHandlers)) {
            return $this->exceptionHandlers[$statusCode]->handleException($exception);
        }

        throw new UndefinedExceptionHandlerException(sprintf(
            'Undefined exception handler for status code "%d".',
            $statusCode
        ));
    }

}
