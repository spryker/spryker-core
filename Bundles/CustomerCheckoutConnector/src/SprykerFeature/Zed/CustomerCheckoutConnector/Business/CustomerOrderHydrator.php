<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerCheckoutConnector\Business;

use Generated\Shared\CustomerCheckoutConnector\CheckoutRequestInterface;
use Generated\Shared\CustomerCheckoutConnector\OrderInterface;
use Generated\Shared\CustomerCheckoutConnector\CustomerInterface;
use Generated\Shared\Transfer\CustomerAddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\SalesAddressTransfer;
use SprykerFeature\Zed\CustomerCheckoutConnector\Dependency\Facade\CustomerCheckoutConnectorToCustomerInterface;

class CustomerOrderHydrator implements CustomerOrderHydratorInterface
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
     * @param OrderInterface $order
     * @param CheckoutRequestInterface $request
     */
    public function hydrateOrderTransfer(OrderInterface $order, CheckoutRequestInterface $request)
    {
        $customerTransfer = $this->getCustomerTransfer();
        $customerTransfer->setEmail($request->getEmail());

        if (!is_null($request->getIdUser())) {
            $customerTransfer->setIdCustomer($request->getIdUser());
            $customerTransfer = $this->customerFacade->getCustomer($customerTransfer);
        } else {
            $customerTransfer->setGuest($request->getGuest());
        }

        if (!is_null($request->getBillingAddress())) {
            $customerTransfer->addBillingAddress($request->getBillingAddress());
            $order->setBillingAddress($this->transformAddresses($request->getBillingAddress()));
        }

        if (!is_null($request->getShippingAddress())) {
            $customerTransfer->addShippingAddress($request->getShippingAddress());
            $order->setShippingAddress($this->transformAddresses($request->getShippingAddress()));
        }

        $order->setCustomer($customerTransfer);
    }

    /**
     * @return CustomerInterface
     */
    protected function getCustomerTransfer()
    {
        return new CustomerTransfer();
    }

    /**
     * @param CustomerAddressTransfer $address
     *
     * @return SalesAddressTransfer
     */
    protected function transformAddresses(CustomerAddressTransfer $address)
    {
        $salesAddress = new SalesAddressTransfer();
        $salesAddress
            ->setAddress1($address->getAddress1())
            ->setAddress2($address->getAddress2())
            ->setAddress3($address->getAddress3())
            ->setCity($address->getCity())
            ->setCompany($address->getCompany())
            ->setEmail($address->getEmail())
            ->setFirstName($address->getFirstName())
            ->setLastName($address->getLastName())
            ->setIso2Code($address->getIso2Code())
            ->setZipCode($address->getZipCode())
        ;

        return $salesAddress;
    }

}
