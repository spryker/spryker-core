<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerCheckoutConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\CustomerCheckoutConnector\Dependency\Facade\CustomerCheckoutConnectorToCustomerBridge;

class CustomerCheckoutConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_CUSTOMER = 'customer facade';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_CUSTOMER] = function (Container $container) {
            return new CustomerCheckoutConnectorToCustomerBridge($container->getLocator()->customer()->facade());
        };

        return $container;
    }

}
