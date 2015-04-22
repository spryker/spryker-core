<?php

namespace SprykerFeature\Zed\Customer\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\Customer\Persistence\CustomerQueryContainer;
use SprykerFeature\Zed\Customer\Business\Customer\Customer;
use SprykerFeature\Zed\Customer\Business\Customer\Address;

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
     * @return CustomerSettings
     */
    public function createSettings()
    {
        return $this->getFactory()->createSettings($this->getLocator());
    }

    /**
     * @return Customer
     */
    public function createCustomer()
    {
        $customer = $this->getFactory()->createCustomerCustomer($this->createQueryContainer(), $this->getLocator());

        $settings = $this->createSettings();

        foreach ($settings->getPasswordRestoredConfirmationSenders() as $sender) {
            $customer->addPasswordRestoredConfirmationSender($sender);
        }

        foreach ($settings->getPasswordRestoreTokenSenders() as $sender) {
            $customer->addPasswordRestoreTokenSender($sender);
        }

        foreach ($settings->getRegistrationTokenSenders() as $sender) {
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
