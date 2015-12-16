<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Yves\Application;

use Spryker\Yves\Application\Plugin\Provider\ExceptionService\DefaultExceptionHandler;
use Spryker\Yves\Application\Plugin\Provider\ExceptionService\ExceptionHandlerDispatcher;
use Spryker\Yves\Application\Plugin\Provider\ExceptionService\ExceptionHandlerInterface;
use Spryker\Yves\Kernel\AbstractFactory;
use Symfony\Component\HttpFoundation\Response;

class ApplicationDependencyContainer extends AbstractFactory
{

    /**
     * @return ExceptionHandlerDispatcher
     */
    public function createExceptionHandlerDispatcher()
    {
        return new ExceptionHandlerDispatcher($this->createExceptionHandlers());
    }

    /**
     * @return ExceptionHandlerInterface[]
     */
    public function createExceptionHandlers()
    {
        return [
            Response::HTTP_NOT_FOUND => new DefaultExceptionHandler(),
        ];
    }

}
