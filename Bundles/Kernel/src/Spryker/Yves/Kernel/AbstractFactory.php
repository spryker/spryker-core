<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel;

use Pyz\Yves\Application\Plugin\Pimple;
use Spryker\Client\Kernel\ClassResolver\Client\ClientResolver;
use Spryker\Shared\Kernel\ContainerGlobals;
use Spryker\Shared\Kernel\Dependency\Injector\DependencyInjector;
use Spryker\Shared\Kernel\Dependency\Injector\DependencyInjectorCollectionInterface;
use Spryker\Yves\Kernel\ClassResolver\DependencyInjector\DependencyInjectorResolver;
use Spryker\Yves\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver;
use Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException;

abstract class AbstractFactory implements FactoryInterface
{

    /**
     * @var \Spryker\Yves\Kernel\Container $container
     */
    private $container;

    /**
     * @var \Spryker\Client\Kernel\AbstractClient
     */
    private $client;

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return $this
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function getContainer()
    {
        $containerGlobals = $this->getContainerGlobals();
        $container = new Container($containerGlobals->getContainerGlobals());

        return $container;
    }

    /**
     * @return \Spryker\Shared\Kernel\ContainerGlobals
     */
    protected function getContainerGlobals()
    {
        return new ContainerGlobals();
    }

    /**
     * @deprecated Use DependencyProvider instead
     *
     * @return \Generated\Client\Ide\AutoCompletion|\Spryker\Shared\Kernel\LocatorLocatorInterface
     */
    protected function getLocator()
    {
        return Locator::getInstance();
    }

    /**
     * @return \Spryker\Client\Kernel\AbstractClient
     */
    protected function getClient()
    {
        if ($this->client === null) {
            $this->client = $this->resolveClient();
        }

        return $this->client;
    }

    /**
     * @return \Spryker\Client\Kernel\AbstractClient
     */
    protected function resolveClient()
    {
        return $this->getClientResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Client\Kernel\ClassResolver\Client\ClientResolver
     */
    protected function getClientResolver()
    {
        return new ClientResolver();
    }

    /**
     * @deprecated Use `$container[ApplicationConstants::APPLICATION_FORM_FACTORY]` within your `DependencyProvider` to get the form factory
     *
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    protected function getFormFactory()
    {
        return (new Pimple())->getApplication()['form.factory'];
    }

    /**
     * @param string $key
     *
     * @throws \Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return mixed
     */
    protected function getProvidedDependency($key)
    {
        if ($this->container === null) {
            $this->container = $this->getContainerWithProvidedDependencies();
        }

        if ($this->container->offsetExists($key) === false) {
            throw new ContainerKeyNotFoundException($this, $key);
        }

        return $this->container[$key];
    }

    /**
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function getContainerWithProvidedDependencies()
    {
        $container = $this->getContainer();
        $dependencyInjectorCollection = $this->resolveDependencyInjectorCollection();
        $dependencyInjector = $this->getDependencyInjector($dependencyInjectorCollection);
        $dependencyProvider = $this->resolveDependencyProvider();

        $container = $this->provideDependencies($dependencyProvider, $container);
        $container = $dependencyInjector->inject($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\AbstractBundleDependencyProvider $dependencyProvider
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function provideDependencies(AbstractBundleDependencyProvider $dependencyProvider, Container $container)
    {
        return $dependencyProvider->provideDependencies($container);
    }

    /**
     * @return \Spryker\Shared\Kernel\Dependency\Injector\DependencyInjectorCollectionInterface
     */
    protected function resolveDependencyInjectorCollection()
    {
        return $this->getDependencyInjectorResolver()->resolve($this);
    }

    /**
     * @param \Spryker\Shared\Kernel\Dependency\Injector\DependencyInjectorCollectionInterface $dependencyInjectorCollection
     *
     * @return \Spryker\Shared\Kernel\Dependency\Injector\DependencyInjector
     */
    protected function getDependencyInjector(DependencyInjectorCollectionInterface $dependencyInjectorCollection)
    {
        return new DependencyInjector($dependencyInjectorCollection);
    }

    /**
     * @return \Spryker\Yves\Kernel\ClassResolver\DependencyInjector\DependencyInjectorResolver
     */
    protected function getDependencyInjectorResolver()
    {
        return new DependencyInjectorResolver();
    }

    /**
     * @return \Spryker\Yves\Kernel\AbstractBundleDependencyProvider
     */
    protected function resolveDependencyProvider()
    {
        return $this->getDependencyProviderResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Yves\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver
     */
    protected function getDependencyProviderResolver()
    {
        return new DependencyProviderResolver();
    }

}
