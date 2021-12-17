<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Kernel\Backend;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\AbstractFactory as GlueAbstractFactory;
use Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider as BackendAbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\ClassResolver\DependencyProviderResolver;
use Spryker\Glue\Kernel\Backend\Container as BackendContainer;
use Spryker\Glue\Kernel\Backend\Exception\InvalidContainerException;
use Spryker\Glue\Kernel\Backend\Exception\InvalidDependencyProviderException;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\Kernel\Backend\Container getContainer()
 * @method setContainer(\Spryker\Glue\Kernel\Backend\Container $container)
 */
abstract class AbstractFactory extends GlueAbstractFactory
{
    /**
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function createContainer(): Container
    {
        $containerGlobals = $this->createContainerGlobals();

        return new BackendContainer($containerGlobals->getContainerGlobals());
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider $dependencyProvider
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @throws \Spryker\Glue\Kernel\Backend\Exception\InvalidDependencyProviderException
     * @throws \Spryker\Glue\Kernel\Backend\Exception\InvalidContainerException
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function provideDependencies(AbstractBundleDependencyProvider $dependencyProvider, Container $container)
    {
        if (!$dependencyProvider instanceof BackendAbstractBundleDependencyProvider) {
            throw new InvalidDependencyProviderException(
                sprintf('Glue backend modules must use the %s', BackendAbstractBundleDependencyProvider::class),
            );
        }

        if (!$container instanceof BackendContainer) {
            throw new InvalidContainerException(sprintf('Glue backend modules must use the %s', BackendContainer::class));
        }

        return $dependencyProvider->provideDependencies($container);
    }

    /**
     * @return \Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider
     */
    protected function resolveDependencyProvider(): AbstractBundleDependencyProvider
    {
        return $this->createDependencyProviderResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Glue\Kernel\Backend\ClassResolver\DependencyProviderResolver
     */
    protected function createDependencyProviderResolver(): DependencyProviderResolver
    {
        return new DependencyProviderResolver();
    }
}
