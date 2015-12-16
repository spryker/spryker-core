<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Yves\Kernel;

use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\Kernel\ClassResolver\Client\ClientNotFoundException;
use Spryker\Client\Kernel\ClassResolver\Client\ClientResolver;
use Spryker\Yves\Kernel\ClassResolver\DependencyContainer\DependencyContainerResolver;
use Spryker\Yves\Kernel\DependencyContainer\DependencyContainerInterface;
use Spryker\Zed\Kernel\ClassResolver\DependencyContainer\DependencyContainerNotFoundException;

abstract class AbstractPlugin
{

    /**
     * @var DependencyContainerInterface
     */
    private $dependencyContainer;

    /**
     * @var AbstractClient
     */
    private $client;

    /**
     * @return DependencyContainerInterface
     */
    protected function getDependencyContainer()
    {
        if ($this->dependencyContainer === null) {
            $this->dependencyContainer = $this->resolveDependencyContainer();
        }

        return $this->dependencyContainer;
    }

    /**
     * @throws DependencyContainerNotFoundException
     *
     * @return AbstractDependencyContainer
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
     * @return AbstractClient
     */
    protected function getClient()
    {
        if ($this->client === null) {
            $this->client = $this->resolveClient();
        }

        return $this->client;
    }

    /**
     * @throws ClientNotFoundException
     *
     * @return AbstractClient
     */
    protected function resolveClient()
    {
        return $this->getClientResolver()->resolve($this);
    }

    /**
     * @return ClientResolver
     */
    protected function getClientResolver()
    {
        return new ClientResolver();
    }

}
