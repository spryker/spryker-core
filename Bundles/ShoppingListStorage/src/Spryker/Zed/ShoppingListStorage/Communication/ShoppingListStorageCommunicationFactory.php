<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ShoppingListStorage\ShoppingListStorageDependencyProvider;

class ShoppingListStorageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ShoppingListStorage\Dependency\Facade\ShoppingListStorageToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(ShoppingListStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    /**
     * @return \Spryker\Zed\ShoppingListStorage\Dependency\Facade\ShoppingListStorageToCompanyUserFacadeInterface
     */
    public function getCompanyUserFacade()
    {
        return $this->getProvidedDependency(ShoppingListStorageDependencyProvider::FACADE_COMPANY_USER);
    }

    /**
     * @return \Spryker\Zed\ShoppingListStorage\Dependency\Facade\ShoppingListStorageToCompanyBusinessUnitFacadeInterface
     */
    public function getCompanyBusinessUnitFacade()
    {
        return $this->getProvidedDependency(ShoppingListStorageDependencyProvider::FACADE_COMPANY_BUSINESS_UNIT);
    }
}
