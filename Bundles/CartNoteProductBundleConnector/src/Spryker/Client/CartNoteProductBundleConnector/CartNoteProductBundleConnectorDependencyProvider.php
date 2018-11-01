<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartNoteProductBundleConnector;

use Spryker\Client\CartNoteProductBundleConnector\Dependency\Client\CartNoteProductBundleConnectorToProductBundleClientBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class CartNoteProductBundleConnectorDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_PRODUCT_BUNDLE = 'CLIENT_PRODUCT_BUNDLE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addProductBundleClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addProductBundleClient(Container $container): Container
    {
        $container[static::CLIENT_PRODUCT_BUNDLE] = function (Container $container) {
            return new CartNoteProductBundleConnectorToProductBundleClientBridge($container->getLocator()->productBundle()->client());
        };

        return $container;
    }
}
