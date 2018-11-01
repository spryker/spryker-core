<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartNoteProductBundleConnector;

use Spryker\Zed\CartNoteProductBundleConnector\Dependency\Facade\CartNoteProductBundleConnectorToProductBundleFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CartNoteProductBundleConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRODUCT_BUNDLE = 'FACADE_PRODUCT_BUNDLE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addProductBundleFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductBundleFacade(Container $container): Container
    {
        $container[static::FACADE_PRODUCT_BUNDLE] = function (Container $container) {
            return new CartNoteProductBundleConnectorToProductBundleFacadeBridge($container->getLocator()->productBundle()->facade());
        };

        return $container;
    }
}
