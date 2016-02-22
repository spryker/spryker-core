<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerCheckoutConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\CustomerCheckoutConnector\CustomerCheckoutConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\CustomerCheckoutConnector\CustomerCheckoutConnectorConfig getConfig()
 */
class CustomerCheckoutConnectorBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\CustomerCheckoutConnector\Business\CustomerOrderHydratorInterface
     */
    public function createCustomerOrderHydrator()
    {
        return new CustomerOrderHydrator(
            $this->getProvidedDependency(CustomerCheckoutConnectorDependencyProvider::FACADE_CUSTOMER)
        );
    }

    /**
     * @return \Spryker\Zed\CustomerCheckoutConnector\Business\CustomerOrderSaverInterface
     */
    public function createCustomerOrderSaver()
    {
        return new CustomerOrderSaver(
            $this->getProvidedDependency(CustomerCheckoutConnectorDependencyProvider::FACADE_CUSTOMER)
        );
    }

    /**
     * @return \Spryker\Zed\CustomerCheckoutConnector\Business\PreConditionCheckerInterface
     */
    public function createPreConditionChecker()
    {
        return new PreConditionChecker(
            $this->getProvidedDependency(CustomerCheckoutConnectorDependencyProvider::FACADE_CUSTOMER)
        );
    }

}
