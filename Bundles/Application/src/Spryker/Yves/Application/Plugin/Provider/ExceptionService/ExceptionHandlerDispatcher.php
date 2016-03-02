<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Application\Plugin\Provider\ExceptionService;

use Spryker\Yves\Application\Plugin\Exception\UndefinedExceptionHandlerException;
use Symfony\Component\Debug\Exception\FlattenException;

class ExceptionHandlerDispatcher
{

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
