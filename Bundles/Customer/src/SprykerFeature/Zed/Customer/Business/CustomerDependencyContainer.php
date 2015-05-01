<?php

namespace SprykerFeature\Zed\Customer\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Shared\Customer\CustomerConfig;
use SprykerFeature\Zed\Customer\Persistence\CustomerQueryContainer;
use SprykerFeature\Zed\Customer\Business\Customer\Customer;
use SprykerFeature\Zed\Customer\Business\Customer\Address;

/**
 * @method CustomerConfig getConfig()
 */
class CustomerDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return CustomerQueryContainer
     */
    public function createQueryContainer()
    {
        return $this->getLocator()->customer()->queryContainer();
    }

    /**
     * @return Customer
     */
    public function createCustomer()
    {
        $customer = $this->getFactory()->createCustomerCustomer($this->createQueryContainer(), $this->getLocator());

        $config = $this->getConfig();

        foreach ($config->getPasswordRestoredConfirmationSenders() as $sender) {
            $customer->addPasswordRestoredConfirmationSender($sender);
        }

        foreach ($config->getPasswordRestoreTokenSenders() as $sender) {
            $customer->addPasswordRestoreTokenSender($sender);
        }

        foreach ($config->getRegistrationTokenSenders() as $sender) {
            $customer->addRegistrationTokenSender($sender);
        }

        return $customer;
    }

    /**
     * @return Address
     */
    public function createAddress()
    {
        return $this->getFactory()->createCustomerAddress($this->createQueryContainer(), $this->getLocator());
    }
}
