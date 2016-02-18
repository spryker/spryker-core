<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToProductBridge;

class TaxProductConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_PRODUCT = 'facade product';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_PRODUCT] = function (Container $container) {
            return new TaxProductConnectorToProductBridge($container->getLocator()->product()->facade());
        };

        return $container;
    }

}
