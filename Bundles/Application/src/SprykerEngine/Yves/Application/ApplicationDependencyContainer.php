<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerEngine\Yves\Application;

use SprykerEngine\Yves\Application\Plugin\ServiceProvider\ExceptionService\ExceptionHandlerDispatcher;
use SprykerEngine\Yves\Application\Plugin\ServiceProvider\ExceptionService\ExceptionHandlerInterface;
use SprykerEngine\Yves\Kernel\AbstractDependencyContainer;
use Symfony\Component\HttpFoundation\Response;

class ApplicationDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return ExceptionHandlerDispatcher
     */
    public function createExceptionHandlerDispatcher()
    {
        return $this->getFactory()->createPluginServiceProviderExceptionServiceExceptionHandlerDispatcher(
            $this->createExceptionHandlers()
        );
    }

    /**
     * @return ExceptionHandlerInterface[]
     */
    public function createExceptionHandlers()
    {
        return [
            Response::HTTP_NOT_FOUND => $this->getFactory()
                ->createPluginServiceProviderExceptionServiceDefaultExceptionHandler(),
        ];
    }

}
