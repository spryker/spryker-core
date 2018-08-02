<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ShoppingListStorage\Dependency\Facade\ShoppingListStorageToCompanyBusinessUnitFacadeBridge;
use Spryker\Zed\ShoppingListStorage\Dependency\Facade\ShoppingListStorageToCompanyUserFacadeBridge;
use Spryker\Zed\ShoppingListStorage\Dependency\Facade\ShoppingListStorageToEventBehaviorFacadeBridge;

class ShoppingListStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    const FACADE_COMPANY_USER = 'FACADE_COMPANY_USER';
    const FACADE_COMPANY_BUSINESS_UNIT = 'FACADE_COMPANY_BUSINESS_UNIT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::FACADE_EVENT_BEHAVIOR] = function (Container $container) {
            return new ShoppingListStorageToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        };

        $container[static::FACADE_COMPANY_USER] = function (Container $container) {
            return new ShoppingListStorageToCompanyUserFacadeBridge($container->getLocator()->companyUser()->facade());
        };

        $container[static::FACADE_COMPANY_BUSINESS_UNIT] = function (Container $container) {
            return new ShoppingListStorageToCompanyBusinessUnitFacadeBridge($container->getLocator()->companyBusinessUnit()->facade());
        };

        return $container;
    }
}
