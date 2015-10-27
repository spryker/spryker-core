<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Application\Communication;

use Generated\Yves\Ide\FactoryAutoCompletion\ApplicationCommunication;
use SprykerEngine\Shared\Config;
use SprykerEngine\Yves\Application\Communication\Plugin\ServiceProvider\ExceptionService\ExceptionHandlerDispatcher;
use SprykerEngine\Yves\Application\Communication\Plugin\ServiceProvider\ExceptionService\ExceptionHandlerInterface;
use SprykerEngine\Yves\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Shared\Yves\YvesConfig;
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
        $exceptionHandlers = [
            Response::HTTP_NOT_FOUND => $this->getFactory()->createPluginServiceProviderExceptionServiceDefaultExceptionHandler(),
        ];

        $internalServerErrorExceptionHandler = $this->getInternalServerErrorExceptionHandler();
        if ($internalServerErrorExceptionHandler !== null) {
            $exceptionHandlers[Response::HTTP_INTERNAL_SERVER_ERROR] = $internalServerErrorExceptionHandler;
        }

        return $exceptionHandlers;
    }

    /**
     * @return ExceptionHandlerInterface|null
     */
    protected function getInternalServerErrorExceptionHandler()
    {
        if (Config::get(YvesConfig::YVES_SHOW_EXCEPTION_STACK_TRACE) === false) {
             return $this->getFactory()->createPluginServiceProviderExceptionServiceInternalServerErrorExceptionHandler();
        }

        return null;
    }

    /**
     * @return Application
     */
    protected function getApplication()
    {
        return $this->getLocator()->application()->pluginPimple()->getApplication();
    }

}
