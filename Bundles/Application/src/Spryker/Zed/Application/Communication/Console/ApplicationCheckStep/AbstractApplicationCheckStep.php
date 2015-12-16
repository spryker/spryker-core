<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Application\Communication\Console\ApplicationCheckStep;

use Spryker\Zed\Kernel\ClassResolver\Facade\FacadeNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\Facade\FacadeResolver;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Application\Communication\ApplicationDependencyContainer;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

abstract class AbstractApplicationCheckStep extends AbstractLogger implements LoggerAwareInterface, LoggerInterface
{

    use LoggerAwareTrait;

    /**
     * @var AbstractFacade
     */
    protected $facade;

    /**
     * @var ApplicationDependencyContainer
     */
    protected $communicationFactory;

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function log($level, $message, array $context = [])
    {
        if ($this->logger) {
            $this->logger->log($level, $message, $context);
        }
    }

    /**
     * @param AbstractCommunicationFactory $communicationFactory
     *
     * @return void
     */
    public function setCommunicationFactory(AbstractCommunicationFactory $communicationFactory)
    {
        $this->communicationFactory = $communicationFactory;
    }

    /**
     * @param Container $container
     *
     * @return void
     */
    public function setExternalDependencies(Container $container)
    {
        $communicationFactory = $this->getCommunicationFactory();
        if (isset($communicationFactory)) {
            $this->getCommunicationFactory()->setContainer($container);
        }
    }

    /**
     * @return AbstractCommunicationFactory
     */
    protected function getCommunicationFactory()
    {
        return $this->communicationFactory;
    }

    /**
     * @param AbstractFacade $facade
     *
     * @return self
     */
    public function setFacade(AbstractFacade $facade)
    {
        $this->facade = $facade;

        return $this;
    }

    /**
     * @return AbstractFacade
     */
    protected function getFacade()
    {
        if ($this->facade === null) {
            $this->facade = $this->resolveFacade();
        }

        return $this->facade;
    }

    /**
     * @throws FacadeNotFoundException
     *
     * @return AbstractFacade
     */
    protected function resolveFacade()
    {
        return $this->getFacadeResolver()->resolve($this);
    }

    /**
     * @return FacadeResolver
     */
    protected function getFacadeResolver()
    {
        return new FacadeResolver();
    }

    abstract public function run();

}
