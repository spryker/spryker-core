<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCartShare;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PersistentCartShare\Dependency\Facade\PersistentCartShareToQuoteFacadeBridge;
use Spryker\Zed\PersistentCartShare\Dependency\Facade\PersistentCartShareToResourceShareFacadeBridge;

/**
 * @method \Spryker\Zed\PersistentCartShare\PersistentCartShareConfig getConfig()
 */
class PersistentCartShareDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_RESOURCE_SHARE = 'FACADE_RESOURCE_SHARE';
    public const FACADE_QUOTE = 'FACADE_QUOTE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addResourceShareFacade($container);
        $container = $this->addQuoteFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteFacade(Container $container): Container
    {
        $container[static::FACADE_QUOTE] = function (Container $container) {
            return new PersistentCartShareToQuoteFacadeBridge($container->getLocator()->quote()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addResourceShareFacade(Container $container): Container
    {
        $container[static::FACADE_RESOURCE_SHARE] = function (Container $container) {
            return new PersistentCartShareToResourceShareFacadeBridge($container->getLocator()->resourceShare()->facade());
        };

        return $container;
    }
}
