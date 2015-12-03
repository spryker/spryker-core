<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerCheckoutConnector\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\CustomerCheckoutConnectorBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\CustomerCheckoutConnector\CustomerCheckoutConnectorConfig;
use SprykerFeature\Zed\CustomerCheckoutConnector\CustomerCheckoutConnectorDependencyProvider;

/**
 * @method CustomerCheckoutConnectorBusiness getFactory()
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
