<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OrderCustomReference;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\OrderCustomReference\Dependency\Client\OrderCustomReferenceToPersistentCartClientBridge;

/**
 * @method \Spryker\Client\OrderCustomReference\OrderCustomReferenceConfig getConfig()
 */
class OrderCustomReferenceDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_PERSISTENT_CART = 'CLIENT_PERSISTENT_CART';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addPersistentCartClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addPersistentCartClient(Container $container): Container
    {
        $container->set(static::CLIENT_PERSISTENT_CART, function (Container $container) {
            return new OrderCustomReferenceToPersistentCartClientBridge($container->getLocator()->persistentCart()->client());
        });

        return $container;
    }
}
