<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerCheckoutConnector\Business;

use Generated\Shared\CustomerCheckoutConnector\OrderInterface;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerFeature\Zed\CustomerCheckoutConnector\Dependency\Facade\CustomerCheckoutConnectorToCustomerInterface;

class CustomerOrderSaver implements CustomerOrderSaverInterface
{

    /**
     * @var CustomerCheckoutConnectorToCustomerInterface
     */
    private $customerFacade;

    /**
     * @param CustomerCheckoutConnectorToCustomerInterface $customerFacade
     */
    public function __construct(CustomerCheckoutConnectorToCustomerInterface $customerFacade)
    {
        $this->customerFacade = $customerFacade;
    }

    /**
     * @param OrderInterface $orderTransfer
     * @param CheckoutResponseTransfer $checkoutResponse
     */
    public function saveOrder(OrderInterface $orderTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $customer = $orderTransfer->getCustomer();

        if ($customer->getGuest()) {
            return;
        }

        if (!is_null($customer->getIdCustomer())) {
            $this->customerFacade->updateCustomer($customer);
        } else {
            $customer->setFirstName($orderTransfer->getBillingAddress()->getFirstName());
            $customer->setLastName($orderTransfer->getBillingAddress()->getLastName());
            $customer->setEmail($orderTransfer->getBillingAddress()->getEmail());
            $customer = $this->customerFacade->registerCustomer($customer);
            $orderTransfer->setCustomer($customer);
        }

        $this->persistAddresses($customer);
    }

    /**
     * @param CustomerTransfer $customer
     */
    protected function persistAddresses(CustomerTransfer $customer)
    {
        foreach ($customer->getBillingAddress() as $billingAddress) {
            if (is_null($billingAddress->getIdCustomerAddress())) {
                $newAddress = $this->customerFacade->createAddress($billingAddress);
                $billingAddress->setIdCustomerAddress($newAddress->getIdCustomerAddress());
            } else {
                $this->customerFacade->updateAddress($billingAddress);
            }
        }

        foreach ($customer->getShippingAddress() as $shippingAddress) {
            if (is_null($shippingAddress->getIdCustomerAddress())) {
                $newAddress = $this->customerFacade->createAddress($shippingAddress);
                $shippingAddress->setIdCustomerAddress($newAddress->getIdCustomerAddress());
            } else {
                $this->customerFacade->updateAddress($shippingAddress);
            }
        }
    }

}
