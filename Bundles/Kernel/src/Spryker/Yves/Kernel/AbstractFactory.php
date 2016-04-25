<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel;

use Pyz\Yves\Application\Plugin\Pimple;
use Spryker\Yves\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver;
use Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException;

abstract class AbstractFactory implements FactoryInterface
{

    /**
     * @var \Spryker\Yves\Kernel\Container $container
     */
    private $container;

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
        return new Container();
    }

    /**
     * @return \Generated\Client\Ide\AutoCompletion|\Spryker\Shared\Kernel\LocatorLocatorInterface
     */
    protected function getLocator()
    {
        return Locator::getInstance();
    }

    /**
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
        $dependencyProvider = $this->resolveDependencyProvider();

        return $dependencyProvider->provideDependencies($container);
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
