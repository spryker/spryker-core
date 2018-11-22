<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomersRestApi;

use Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery;
use Spryker\Zed\CustomersRestApi\Dependency\Facade\CustomersRestApiToCustomerFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CustomersRestApi\CustomersRestApiConfig getConfig()
 */
class CustomersRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPEL_QUERY_CUSTOMER_ADDRESS = 'PROPEL_QUERY_CUSTOMER_ADDRESS';
    public const FACADE_CUSTOMER = 'FACADE_CUSTOMER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        parent::providePersistenceLayerDependencies($container);
        $container = $this->addCustomerAddressPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        parent::provideBusinessLayerDependencies($container);
        $container = $this->addCustomerFacade($container);

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

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerFacade(Container $container): Container
    {
        $container[static::FACADE_CUSTOMER] = function (Container $container) {
            return new CustomersRestApiToCustomerFacadeBridge($container->getLocator()->customer()->facade());
        };

        return $container;
    }
}
