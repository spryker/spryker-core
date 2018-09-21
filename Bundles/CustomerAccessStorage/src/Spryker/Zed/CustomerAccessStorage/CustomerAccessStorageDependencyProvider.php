<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccessStorage;

use Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccessQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CustomerAccessStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPEL_QUERY_CUSTOMER_ACCESS = 'QUERY_CUSTOMER_ACCESS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container = parent::providePersistenceLayerDependencies($container);
        $container = $this->addCustomerAccessPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerAccessPropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_CUSTOMER_ACCESS] = function (Container $container) {
            return SpyUnauthenticatedCustomerAccessQuery::create();
        };

        return $container;
    }
}
