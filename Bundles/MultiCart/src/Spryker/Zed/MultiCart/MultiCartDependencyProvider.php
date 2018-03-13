<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCart;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToPersistentCartFacadeBridge;
use Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToQuoteFacadeBridge;

class MultiCartDependencyProvider extends AbstractBundleDependencyProvider
{
    public const QUERY_QUOTE = 'QUERY_QUOTE';
    public const REPOSITORY_QUOTE = 'REPOSITORY_QUOTE';
    public const FACADE_QUOTE = 'FACADE_QUOTE';
    public const FACADE_PERSISTENT_CART = 'FACADE_PERSISTENT_CART';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addQuoteFacade($container);
        $container = $this->addPersistentCartFacade($container);

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
            return new MultiCartToQuoteFacadeBridge($container->getLocator()->quote()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPersistentCartFacade(Container $container): Container
    {
        $container[static::FACADE_PERSISTENT_CART] = function (Container $container) {
            return new MultiCartToPersistentCartFacadeBridge($container->getLocator()->persistentCart()->facade());
        };

        return $container;
    }
}
