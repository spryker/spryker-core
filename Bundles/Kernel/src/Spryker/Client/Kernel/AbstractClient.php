<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Kernel;

use Spryker\Client\Kernel\ClassResolver\DependencyContainer\DependencyContainerNotFoundException;
use Spryker\Client\Kernel\ClassResolver\DependencyContainer\DependencyContainerResolver;
use Spryker\Client\Kernel\DependencyContainer\DependencyContainerInterface;
use Spryker\Client\ZedRequest\Stub\BaseStub;
use Spryker\Shared\ZedRequest\Client\Message;

abstract class AbstractClient
{

    /**
     * @var DependencyContainerInterface
     */
    private $dependencyContainer;

    /**
     * @var Container
     */
    private $container;

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
     * @return DependencyContainerInterface
     */
    protected function getDependencyContainer()
    {
        if ($this->dependencyContainer === null) {
            $this->dependencyContainer = $this->resolveDependencyContainer();
        }

        if ($this->container !== null) {
            $this->dependencyContainer->setContainer($this->container);
        }

        return $this->dependencyContainer;
    }

    /**
     * @throws DependencyContainerNotFoundException
     *
     * @return DependencyContainerInterface
     */
    protected function resolveDependencyContainer()
    {
        return $this->getDependencyContainerResolver()->resolve($this);
    }

    /**
     * @return DependencyContainerResolver
     */
    protected function getDependencyContainerResolver()
    {
        return new DependencyContainerResolver();
    }

    /**
     * @return BaseStub
     */
    protected function getZedStub()
    {
        $dependencyContainer = $this->getDependencyContainer();
        if (!method_exists($dependencyContainer, 'createZedStub')) {
            throw new \BadMethodCallException(
                sprintf('createZedStub method is not implemented in "%s".', get_class($dependencyContainer))
            );
        }

        return $this->getDependencyContainer()->createZedStub();
    }

    /**
     * @return Message[]
     */
    public function getZedInfoMessages()
    {
        return $this->getZedStub()->getInfoMessages();
    }

    /**
     * @return Message[]
     */
    public function getZedSuccessMessages()
    {
        return $this->getZedStub()->getSuccessMessages();
    }

    /***
     * @return Message[]
     */
    public function getZedErrorMessages()
    {
        return $this->getZedStub()->getErrorMessages();
    }

}
