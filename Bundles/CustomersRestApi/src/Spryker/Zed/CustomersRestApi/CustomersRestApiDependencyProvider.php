<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomersRestApi;

use Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CustomersRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPEL_QUERY_CUSTOMER_ADDRESS = 'PROPEL_QUERY_CUSTOMER_ADDRESS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = $this->addCustomerAddressPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerAddressPropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_CUSTOMER_ADDRESS] = function () {
            return SpyCustomerAddressQuery::create();
        };

        return $container;
    }
}
