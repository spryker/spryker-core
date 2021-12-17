<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Kernel\Backend;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider as GlueAbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\Exception\InvalidContainerException;
use Spryker\Glue\Kernel\Container as GlueContainer;

abstract class AbstractBundleDependencyProvider extends GlueAbstractBundleDependencyProvider
{
    /**
     * Use {@see \Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider::provideBackendDependencies()} instead
     * to enable auto-completion for facades
     *
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @throws \Spryker\Glue\Kernel\Backend\Exception\InvalidContainerException
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    public function provideDependencies(GlueContainer $container)
    {
        if (!$container instanceof Container) {
            throw new InvalidContainerException(
                sprintf('%s::%s() should only return %s', static::class, __METHOD__, Container::class),
            );
        }

        /** @var \Spryker\Glue\Kernel\Backend\Container $container */
        $container = parent::provideDependencies($container);

        return $this->provideBackendDependencies($container);
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    public function provideBackendDependencies(Container $container): Container
    {
        return $container;
    }
}
