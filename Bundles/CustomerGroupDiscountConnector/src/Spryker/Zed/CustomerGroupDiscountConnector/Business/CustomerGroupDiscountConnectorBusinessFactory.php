<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroupDiscountConnector\Business;

use Spryker\Zed\CustomerGroupDiscountConnector\Business\DecisionRule\CustomerGroupDecisionRule;
use Spryker\Zed\CustomerGroupDiscountConnector\CustomerGroupDiscountConnectorDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CustomerGroupDiscountConnector\CustomerGroupDiscountConnectorConfig getConfig()
 */
class CustomerGroupDiscountConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CustomerGroupDiscountConnector\Business\DecisionRule\CustomerGroupDecisionRuleInterface
     */
    public function createCustomerGroupDecisionRule()
    {
        return new CustomerGroupDecisionRule(
            $this->getDiscountFacade(),
            $this->getCustomerGroupFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CustomerGroupDiscountConnector\Dependency\Facade\CustomerGroupDiscountConnectorToDiscountFacadeInterface
     */
    protected function getDiscountFacade()
    {
        return $this->getProvidedDependency(CustomerGroupDiscountConnectorDependencyProvider::FACADE_DISCOUNT);
    }

    /**
     * @return \Spryker\Zed\CustomerGroupDiscountConnector\Dependency\Facade\CustomerGroupDiscountConnectorToCustomerGroupFacadeInterface
     */
    protected function getCustomerGroupFacade()
    {
        return $this->getProvidedDependency(CustomerGroupDiscountConnectorDependencyProvider::FACADE_CUSTOMER_GROUP);
    }
}
