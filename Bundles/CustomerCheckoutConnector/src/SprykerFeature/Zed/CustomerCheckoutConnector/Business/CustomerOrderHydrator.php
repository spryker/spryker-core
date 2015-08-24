<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerCheckoutConnector\Business;

use Generated\Shared\CustomerCheckoutConnector\CheckoutRequestInterface;
use Generated\Shared\CustomerCheckoutConnector\OrderInterface;
use Generated\Shared\Transfer\CustomerAddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
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
        $customerTransfer = new CustomerTransfer();

        $idUser = $request->getIdUser();
        if (null !== $idUser) {
            $customerTransfer->setIdCustomer($idUser);

            $customerTransfer->setEmail($request->getEmail());
            $customerTransfer = $this->customerFacade->getCustomer($customerTransfer);
        } else {
            $customerTransfer->setGuest($request->getGuest());
        }

        $billingAddress = $request->getBillingAddress();
        if (null !== $billingAddress) {
            $order->setBillingAddress($billingAddress);

            $customerAddressEntity = new CustomerAddressTransfer();
            $customerAddressEntity->fromArray($billingAddress->toArray(), true);

            $customerTransfer->addBillingAddress($customerAddressEntity);
        }

        $shippingAddress = $request->getShippingAddress();
        if (null !== $shippingAddress) {
            $order->setShippingAddress($shippingAddress);

            $customerAddressEntity = new CustomerAddressTransfer();
            $customerAddressEntity->fromArray($shippingAddress->toArray(), true);

            $customerTransfer->addShippingAddress($customerAddressEntity);
        }

        $order->setCustomer($customerTransfer);
    }

}
