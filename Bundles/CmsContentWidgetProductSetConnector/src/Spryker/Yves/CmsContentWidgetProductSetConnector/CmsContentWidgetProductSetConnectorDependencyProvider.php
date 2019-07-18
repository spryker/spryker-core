<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsContentWidgetProductSetConnector;

use Spryker\Yves\CmsContentWidgetProductSetConnector\Dependency\Client\CmsContentWidgetProductSetConnectorToProductBridge;
use Spryker\Yves\CmsContentWidgetProductSetConnector\Dependency\Client\CmsContentWidgetProductSetConnectorToProductSetBridgeSet;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class CmsContentWidgetProductSetConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PRODUCT_SET_CLIENT = 'PRODUCT SET CLIENT';
    public const PRODUCT_CLIENT = 'PRODUCT CLIENT';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container[static::PRODUCT_SET_CLIENT] = function (Container $container) {
            return new CmsContentWidgetProductSetConnectorToProductSetBridgeSet($container->getLocator()->productSet()->client());
        };

        $container[static::PRODUCT_CLIENT] = function (Container $container) {
            return new CmsContentWidgetProductSetConnectorToProductBridge($container->getLocator()->product()->client());
        };

        return $container;
    }
}
