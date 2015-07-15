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
        return $this->getFactory()->createCustomerOrderHydrator(
            $this->getProvidedDependency(CustomerCheckoutConnectorDependencyProvider::FACADE_CUSTOMER)
        );
    }

    /**
     * @return CustomerOrderSaverInterface
     */
    public function createCustomerOrderSaver()
    {
        return $this->getFactory()->createCustomerOrderSaver(
            $this->getProvidedDependency(CustomerCheckoutConnectorDependencyProvider::FACADE_CUSTOMER)
        );
    }

    /**
     * @return PreconditionCheckerInterface
     */
    public function createPreconditionChecker()
    {
        return $this->getFactory()->createPreconditionChecker(
            $this->getProvidedDependency(CustomerCheckoutConnectorDependencyProvider::FACADE_CUSTOMER)
        );
    }

}
