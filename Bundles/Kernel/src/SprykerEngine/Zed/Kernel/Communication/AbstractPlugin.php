<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\Communication;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerEngine\Zed\Kernel\ClassResolver\DependencyContainer\DependencyContainerNotFoundException;
use SprykerEngine\Zed\Kernel\ClassResolver\DependencyContainer\DependencyContainerResolver;
use SprykerEngine\Zed\Kernel\ClassResolver\DependencyProvider\DependencyProviderNotFoundException;
use SprykerEngine\Zed\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver;
use SprykerEngine\Zed\Kernel\ClassResolver\Facade\FacadeNotFoundException;
use SprykerEngine\Zed\Kernel\ClassResolver\Facade\FacadeResolver;
use SprykerEngine\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerNotFoundException;
use SprykerEngine\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerResolver;
use SprykerEngine\Zed\Kernel\Container;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;

abstract class AbstractPlugin
{

    /**
     * @var AbstractFacade
     */
    private $facade;

    /**
     * @var AbstractCommunicationDependencyContainer
     */
    private $dependencyContainer;

    /**
     * @var Container
     */
    private $container;

    /**
     * @var AbstractQueryContainer
     */
    private $queryContainer;

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

    /**
     * @return AbstractCommunicationDependencyContainer
     */
    protected function getDependencyContainer()
    {
        if ($this->dependencyContainer === null) {
            $this->dependencyContainer = $this->resolveDependencyContainer();

            if ($this->container === null) {
                $dependencyProvider = $this->resolveDependencyProvider();
                $container = new Container();
                $dependencyProvider->provideCommunicationLayerDependencies($container);
                $this->container = $container;
            }

            $this->dependencyContainer->setContainer($this->container);

            if ($this->queryContainer !== null) {
                $this->dependencyContainer->setQueryContainer($this->getQueryContainer());
            }
        }

        return $this->dependencyContainer;
    }

    /**
     * @throws DependencyContainerNotFoundException
     *
     * @return AbstractCommunicationDependencyContainer
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
     * @throws DependencyProviderNotFoundException
     *
     * @return AbstractBundleDependencyProvider
     */
    protected function resolveDependencyProvider()
    {
        return $this->getDependencyProviderResolver()->resolve($this);
    }

    /**
     * @return DependencyProviderResolver
     */
    protected function getDependencyProviderResolver()
    {
        return new DependencyProviderResolver();
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
        if ($this->queryContainer === null) {
            $this->queryContainer = $this->resolveQueryContainer();
        }

        return $this->queryContainer;
    }

    /**
     * @throws QueryContainerNotFoundException
     *
     * @return AbstractQueryContainer
     */
    protected function resolveQueryContainer()
    {
        return $this->getQueryContainerResolver()->resolve($this);
    }

    /**
     * @return QueryContainerResolver
     */
    protected function getQueryContainerResolver()
    {
        return new QueryContainerResolver();
    }

}
