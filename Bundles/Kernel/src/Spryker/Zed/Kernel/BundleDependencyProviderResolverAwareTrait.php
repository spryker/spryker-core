<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel;

use Spryker\Zed\Kernel\ClassResolver\DependencyProvider\DependencyProviderNotFoundException;
use Spryker\Zed\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver;
use Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException;

trait BundleDependencyProviderResolverAwareTrait
{

    /**
     * @var Container
     */
    private $container;

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
     *
     * @return void
     */
    abstract protected function provideExternalDependencies(
        AbstractBundleDependencyProvider $dependencyProvider,
        Container $container
    );

}
