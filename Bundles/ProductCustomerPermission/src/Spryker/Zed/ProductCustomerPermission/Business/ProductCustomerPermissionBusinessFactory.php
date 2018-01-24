<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCustomerPermission\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductCustomerPermission\Business\Model\CustomerProductPermissionSaver;
use Spryker\Zed\ProductCustomerPermission\ProductCustomerPermissionDependencyProvider;

/**
 * @method \Spryker\Zed\ProductCustomerPermission\ProductCustomerPermissionConfig getConfig()
 * @method \Spryker\Zed\ProductCustomerPermission\Persistence\ProductCustomerPermissionQueryContainerInterface getQueryContainer()
 */
class ProductCustomerPermissionBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @param int $customerId
     *
     * @return \Spryker\Zed\ProductCustomerPermission\Business\Model\CustomerProductPermissionSaver
     */
    public function createCustomerProductPermissionSaver(int $customerId)
    {
        return new CustomerProductPermissionSaver($customerId, $this->getQueryContainer(), $this->getTouchFacade());
    }

    /**
     * @return \Spryker\Zed\ProductCustomerPermission\Dependency\Facade\ProductCustomerPermissionToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(ProductCustomerPermissionDependencyProvider::FACADE_TOUCH);
    }
}
