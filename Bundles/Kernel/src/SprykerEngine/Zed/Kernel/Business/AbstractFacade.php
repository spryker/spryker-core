<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\Business;

use SprykerEngine\Zed\Kernel\Business\DependencyContainer\DependencyContainerInterface;
use SprykerEngine\Zed\Kernel\ClassResolver\DependencyContainer\DependencyContainerNotFoundException;
use SprykerEngine\Zed\Kernel\ClassResolver\DependencyContainer\DependencyContainerResolver;
use SprykerEngine\Zed\Kernel\Container;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;

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
    public function setOwnQueryContainer(AbstractQueryContainer $queryContainer)
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

        if ($this->getQueryContainer() !== null) {
            $this->dependencyContainer->setQueryContainer($this->getQueryContainer());
        }

        if ($this->getContainer() !== null) {
            $this->dependencyContainer->setContainer($this->getContainer());
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
        $classResolver = new DependencyContainerResolver();

        return $classResolver->resolve($this);
    }

}
