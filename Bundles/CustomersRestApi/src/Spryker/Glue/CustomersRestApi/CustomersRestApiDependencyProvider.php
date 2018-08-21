<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi;

use Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientBridge;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToSessionClientBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

class CustomersRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_SESSION = 'CLIENT_SESSION';
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';
    public const PROPEL_QUERY_CUSTOMER_ADDRESS = 'PROPEL_QUERY_CUSTOMER_ADDRESS';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addSessionClient($container);
        $container = $this->addCustomerClient($container);
        $container = $this->addCustomerAddressPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addSessionClient(Container $container): Container
    {
        $container[static::CLIENT_SESSION] = function (Container $container) {
            return new CustomersRestApiToSessionClientBridge($container->getLocator()->session()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container[static::CLIENT_CUSTOMER] = function (Container $container) {
            return new CustomersRestApiToCustomerClientBridge($container->getLocator()->customer()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCustomerAddressPropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_CUSTOMER_ADDRESS] = function () {
            return SpyCustomerAddressQuery::create();
        };

        return $container;
    }
}
