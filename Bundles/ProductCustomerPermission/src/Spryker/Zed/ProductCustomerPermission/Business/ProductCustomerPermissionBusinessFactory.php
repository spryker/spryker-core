<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCustomerPermission\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductCustomerPermission\Business\Model\ProductCustomerPermissionCartValidator;
use Spryker\Zed\ProductCustomerPermission\Business\Model\ProductCustomerPermissionCheckoutPreCondition;
use Spryker\Zed\ProductCustomerPermission\Business\Model\ProductCustomerPermissionSaver;
use Spryker\Zed\ProductCustomerPermission\ProductCustomerPermissionDependencyProvider;

/**
 * @method \Spryker\Zed\ProductCustomerPermission\ProductCustomerPermissionConfig getConfig()
 * @method \Spryker\Zed\ProductCustomerPermission\Persistence\ProductCustomerPermissionQueryContainerInterface getQueryContainer()
 */
class ProductCustomerPermissionBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductCustomerPermission\Business\Model\ProductCustomerPermissionSaverInterface
     */
    public function createProductCustomerPermissionSaver()
    {
        return new ProductCustomerPermissionSaver($this->getQueryContainer(), $this->getTouchFacade());
    }

    /**
     * @return \Spryker\Zed\ProductCustomerPermission\Business\Model\ProductCustomerPermissionCartValidatorInterface
     */
    public function createCartValidator()
    {
        return new ProductCustomerPermissionCartValidator($this->getQueryContainer(), $this->getProductFacade());
    }

    /**
     * @return \Spryker\Zed\ProductCustomerPermission\Business\Model\ProductCustomerPermissionCheckoutPreConditionInterface
     */
    public function createCheckoutPreConditionChecker()
    {
        return new ProductCustomerPermissionCheckoutPreCondition($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductCustomerPermission\Dependency\Facade\ProductCustomerPermissionToTouchFacadeInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(ProductCustomerPermissionDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToProductInterface
     */
    protected function getProductFacade()
    {
        return $this->getProvidedDependency(ProductCustomerPermissionDependencyProvider::FACADE_PRODUCT);
    }
}
