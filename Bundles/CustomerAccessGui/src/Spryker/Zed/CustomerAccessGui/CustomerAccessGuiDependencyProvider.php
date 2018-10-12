<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccessGui;

use Spryker\Zed\CustomerAccessGui\Dependency\Facade\CustomerAccessGuiToCustomerAccessFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CustomerAccessGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_CUSTOMER_ACCESS = 'FACADE_CUSTOMER_ACCESS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addCustomerAccessFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerAccessFacade(Container $container): Container
    {
        $container[static::FACADE_CUSTOMER_ACCESS] = function (Container $container) {
            return new CustomerAccessGuiToCustomerAccessFacadeBridge($container->getLocator()->customerAccess()->facade());
        };

        return $container;
    }
}
