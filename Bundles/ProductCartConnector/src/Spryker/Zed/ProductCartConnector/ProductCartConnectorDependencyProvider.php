<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCartConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToLocaleBridge;
use Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToMessengerFacadeBridge;
use Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToProductBridge;

class ProductCartConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_MESSENGER = 'FACADE_MESSENGER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new ProductCartConnectorToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[self::FACADE_PRODUCT] = function (Container $container) {
            return new ProductCartConnectorToProductBridge($container->getLocator()->product()->facade());
        };

        $container = $this->addMessengerFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMessengerFacade(Container $container): Container
    {
        $container[static::FACADE_MESSENGER] = function (Container $container) {
            return new ProductCartConnectorToMessengerFacadeBridge($container->getLocator()->messenger()->facade());
        };

        return $container;
    }
}
