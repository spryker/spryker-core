<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\Communication;

use Psr\Log\AbstractLogger;
use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerEngine\Zed\Kernel\ClassResolver\DependencyContainer\DependencyContainerResolver;
use SprykerEngine\Zed\Kernel\Container;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;

abstract class AbstractPlugin extends AbstractLogger implements MessengerInterface
{

    const DEPENDENCY_CONTAINER = 'DependencyContainer';

    /**
     * @var MessengerInterface
     */
    protected $messenger;

    /**
     * @var AbstractFacade
     */
    private $facade;

    /**
     * @var AbstractCommunicationDependencyContainer
     */
    private $dependencyContainer;

    /**
     * @var AbstractQueryContainer
     */
    private $queryContainer;

    /**
     * @var Container
     */
    private $container;

    /**
     * @param MessengerInterface $messenger
     *
     * @return self
     */
    public function setMessenger(MessengerInterface $messenger)
    {
        $this->messenger = $messenger;

        return $this;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return self
     */
    public function log($level, $message, array $context = [])
    {
        if ($this->messenger) {
            $this->messenger->log($level, $message, $context);
        }

        return $this;
    }

    /**
     * @param Container $container
     *
     * @return self
     */
    public function setExternalDependencies(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @param AbstractFacade $facade
     *
     * @return self
     */
    public function setOwnFacade(AbstractFacade $facade)
    {
        $this->facade = $facade;

        return $this;
    }

    /**
     * @return AbstractFacade
     */
    protected function getFacade()
    {
        return $this->facade;
    }

    /**
     * @param AbstractCommunicationDependencyContainer $dependencyContainer
     *
     * @return self
     */
    public function setDependencyContainer(AbstractCommunicationDependencyContainer $dependencyContainer)
    {
        $this->dependencyContainer = $dependencyContainer;

        return $this;
    }

    /**
     * @return AbstractCommunicationDependencyContainer
     */
    protected function getDependencyContainer()
    {
        if ($this->dependencyContainer === null) {
            $this->dependencyContainer = $this->findDependencyContainer();
        }

        if ($this->getQueryContainer() !== null) {
            $this->dependencyContainer->setQueryContainer($this->getQueryContainer());
        }

        if ($this->container !== null) {
            $this->dependencyContainer->setContainer($this->container);
        }

        return $this->dependencyContainer;
    }

    /**
     * @throws \Exception
     *
     * @return mixed
     */
    private function findDependencyContainer()
    {
        $classResolver = new DependencyContainerResolver();

        return $classResolver->resolve($this);
    }

    /**
     * @param AbstractQueryContainer $queryContainer
     *
     * @return self
     */
    public function setQueryContainer(AbstractQueryContainer $queryContainer)
    {
        $this->queryContainer = $queryContainer;

        return $this;
    }

    /**
     * @return AbstractQueryContainer
     */
    protected function getQueryContainer()
    {
        return $this->queryContainer;
    }

}
