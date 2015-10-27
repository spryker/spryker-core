<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Application\Communication;

use Generated\Yves\Ide\FactoryAutoCompletion\ApplicationCommunication;
use SprykerEngine\Yves\Application\Communication\Plugin\ServiceProvider\ExceptionService\ExceptionHandlerDispatcher;
use SprykerEngine\Yves\Application\Communication\Plugin\ServiceProvider\ExceptionService\ExceptionHandlerInterface;
use SprykerEngine\Yves\Kernel\Communication\AbstractCommunicationDependencyContainer;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method ApplicationCommunication getFactory()
 */
class ApplicationDependencyContainer extends AbstractCommunicationDependencyContainer
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

    /**
     * @return Application
     */
    protected function getApplication()
    {
        return $this->getLocator()->application()->pluginPimple()->getApplication();
    }

}
