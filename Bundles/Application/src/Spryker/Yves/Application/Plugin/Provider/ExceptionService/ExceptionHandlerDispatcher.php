<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Application\Plugin\Provider\ExceptionService;

use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Application\Plugin\Exception\UndefinedExceptionHandlerException;
use Symfony\Component\ErrorHandler\Exception\FlattenException;

class ExceptionHandlerDispatcher
{
    use LoggerTrait;

    /**
     * @var \Spryker\Yves\Application\Plugin\Provider\ExceptionService\ExceptionHandlerInterface[]
     */
    protected $exceptionHandlers;

    /**
     * @param \Spryker\Yves\Application\Plugin\Provider\ExceptionService\ExceptionHandlerInterface[] $exceptionHandlers
     */
    public function __construct(array $exceptionHandlers)
    {
        $this->exceptionHandlers = $exceptionHandlers;
    }

    /**
     * @param \Symfony\Component\ErrorHandler\Exception\FlattenException $exception
     *
     * @throws \Spryker\Yves\Application\Plugin\Exception\UndefinedExceptionHandlerException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function dispatch(FlattenException $exception)
    {
        $statusCode = $exception->getStatusCode();

        if (isset($this->exceptionHandlers[$statusCode])) {
            $this->getLogger()->error(
                $exception->getMessage(),
                [
                    'exception' => $exception,
                ]
            );

            return $this->exceptionHandlers[$statusCode]->handleException($exception);
        }

        throw new UndefinedExceptionHandlerException(sprintf(
            'Undefined exception handler for status code "%d".',
            $statusCode
        ));
    }
}
