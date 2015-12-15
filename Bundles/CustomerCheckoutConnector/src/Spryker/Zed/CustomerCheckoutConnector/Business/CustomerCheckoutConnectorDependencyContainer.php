<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CustomerCheckoutConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use Spryker\Zed\CustomerCheckoutConnector\CustomerCheckoutConnectorConfig;
use Spryker\Zed\CustomerCheckoutConnector\CustomerCheckoutConnectorDependencyProvider;

/**
 * @method CustomerCheckoutConnectorConfig getConfig()
 */
class CustomerCheckoutConnectorDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return CustomerOrderHydratorInterface
     */
    public function createCustomerOrderHydrator()
    {
        return new CustomerOrderHydrator(
            $this->getProvidedDependency(CustomerCheckoutConnectorDependencyProvider::FACADE_CUSTOMER)
        );
    }

    /**
     * @return CustomerOrderSaverInterface
     */
    public function createCustomerOrderSaver()
    {
        return new CustomerOrderSaver(
            $this->getProvidedDependency(CustomerCheckoutConnectorDependencyProvider::FACADE_CUSTOMER)
        );
    }

    /**
     * @return PreConditionCheckerInterface
     */
    public function createPreConditionChecker()
    {
        return new PreConditionChecker(
            $this->getProvidedDependency(CustomerCheckoutConnectorDependencyProvider::FACADE_CUSTOMER)
        );
    }

}
