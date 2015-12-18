<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel;

use Spryker\Zed\Kernel\ClassResolver\Config\BundleConfigResolver;
use Spryker\Zed\Kernel\ClassResolver\DependencyProvider\DependencyProviderNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver;
use Spryker\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\QueryContainer\QueryContainerResolver;
use Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

abstract class AbstractFactory
{

    /**
     * @var AbstractBundleConfig
     */
    private $config;

    /**
     * @var Container
     */
    private $container;

    /**
     * @var AbstractQueryContainer
     */
    private $queryContainer;

    /**
     * @param Container $container
     *
     * @return self
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @param string $key
     *
     * @throws ContainerKeyNotFoundException
     *
     * @return mixed
     */
    public function getProvidedDependency($key)
    {
        if ($this->container === null) {
            $dependencyProvider = $this->resolveDependencyProvider();
            $container = new Container();
            $this->provideExternalDependencies($dependencyProvider, $container);
            $this->container = $container;
        }

        if ($this->container->offsetExists($key) === false) {
            throw new ContainerKeyNotFoundException($this, $key);
        }

        return $this->container[$key];
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
     * @param AbstractBundleDependencyProvider $dependencyProvider
     * @param Container $container
     */
    abstract protected function provideExternalDependencies(
        AbstractBundleDependencyProvider $dependencyProvider,
        Container $container
    );

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
     * @param AbstractBundleConfig $config
     *
     * @return self
     */
    public function setConfig(AbstractBundleConfig $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @TODO this method should not be public @see spryker/spryker#940
     *
     * @return AbstractBundleConfig
     */
    public function getConfig()
    {
        if ($this->config === null) {
            $this->config = $this->resolveBundleConfig();
        }

        return $this->config;
    }

    /**
     * @throws \Exception
     *
     * @return AbstractBundleConfig
     */
    protected function resolveBundleConfig()
    {
        $resolver = new BundleConfigResolver();

        return $resolver->resolve($this);
    }

    /**
     * @return QueryContainerResolver
     */
    protected function getQueryContainerResolver()
    {
        return new QueryContainerResolver();
    }

}
