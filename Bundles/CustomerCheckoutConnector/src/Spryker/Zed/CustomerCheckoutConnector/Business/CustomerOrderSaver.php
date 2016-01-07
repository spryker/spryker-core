<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CustomerCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CustomerCheckoutConnector\Dependency\Facade\CustomerCheckoutConnectorToCustomerInterface;

class CustomerOrderSaver implements CustomerOrderSaverInterface
{

    /**
     * @var \Spryker\Zed\CustomerCheckoutConnector\Dependency\Facade\CustomerCheckoutConnectorToCustomerInterface
     */
    private $customerFacade;

    /**
     * @param \Spryker\Zed\CustomerCheckoutConnector\Dependency\Facade\CustomerCheckoutConnectorToCustomerInterface $customerFacade
     */
    public function __construct(CustomerCheckoutConnectorToCustomerInterface $customerFacade)
    {
        $this->customerFacade = $customerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $customerTransfer = $quoteTransfer->getCustomer();

        if ($customerTransfer->getIsGuest()) {
            return;
        }

        if ($customerTransfer->getIdCustomer() !== null) {
            $this->customerFacade->updateCustomer($customerTransfer);
        } else {
            $customerTransfer->setFirstName($quoteTransfer->getBillingAddress()->getFirstName());
            $customerTransfer->setLastName($quoteTransfer->getBillingAddress()->getLastName());
            if (!$customerTransfer->getEmail()) {
                $customerTransfer->setEmail($quoteTransfer->getBillingAddress()->getEmail());
            }
            $customerResponseTransfer = $this->customerFacade->registerCustomer($customerTransfer);
            $quoteTransfer->setCustomer($customerResponseTransfer->getCustomerTransfer());
            //$quoteTransfer->setFkCustomer($customerResponseTransfer->getCustomerTransfer()->getIdCustomer());
        }

        $this->persistAddresses($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customer
     *
     * @return void
     */
    protected function persistAddresses(CustomerTransfer $customer)
    {
        foreach ($customer->getBillingAddress() as $billingAddress) {
            $billingAddress->setFkCustomer($customer->getIdCustomer());
            if ($billingAddress->getIdCustomerAddress() === null) {
                $newAddress = $this->customerFacade->createAddress($billingAddress);
                $billingAddress->setIdCustomerAddress($newAddress->getIdCustomerAddress());
            } else {
                $this->customerFacade->updateAddress($billingAddress);
            }
        }

        foreach ($customer->getShippingAddress() as $shippingAddress) {
            $shippingAddress->setFkCustomer($customer->getIdCustomer());
            if ($shippingAddress->getIdCustomerAddress() === null) {
                $newAddress = $this->customerFacade->createAddress($shippingAddress);
                $shippingAddress->setIdCustomerAddress($newAddress->getIdCustomerAddress());
            } else {
                $this->customerFacade->updateAddress($shippingAddress);
            }
        }
    }

}
