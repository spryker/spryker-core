<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Business;

use Spryker\Zed\Kernel\Business\DependencyContainer\DependencyContainerInterface;
use Spryker\Zed\Kernel\ClassResolver\DependencyContainer\DependencyContainerNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\DependencyContainer\DependencyContainerResolver;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

abstract class AbstractFacade implements FacadeInterface
{

    /**
     * @var DependencyContainerInterface
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
     * @param AbstractBusinessDependencyContainer $businessDependencyContainer
     *
     * @return self
     */
    public function setDependencyContainer(AbstractBusinessDependencyContainer $businessDependencyContainer)
    {
        $this->dependencyContainer = $businessDependencyContainer;

        return $this;
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
     * @return Container
     */
    protected function getContainer()
    {
        return $this->container;
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

        if ($this->queryContainer !== null) {
            $this->dependencyContainer->setQueryContainer($this->queryContainer);
        }

        return $this->dependencyContainer;
    }

    /**
     * @throws DependencyContainerNotFoundException
     *
     * @return AbstractBusinessDependencyContainer
     */
    private function resolveDependencyContainer()
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

}
