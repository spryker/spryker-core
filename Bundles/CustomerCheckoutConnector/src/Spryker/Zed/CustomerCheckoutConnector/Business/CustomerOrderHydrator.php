<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerCheckoutConnector\Business;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\CustomerCheckoutConnector\Dependency\Facade\CustomerCheckoutConnectorToCustomerInterface;

class CustomerOrderHydrator implements CustomerOrderHydratorInterface
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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $request
     *
     * @return void
     */
    public function hydrateOrderTransfer(OrderTransfer $orderTransfer, CheckoutRequestTransfer $request)
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setEmail($request->getEmail());
        $customerTransfer->setPassword($request->getCustomerPassword());

        $this->setGuestProperties($orderTransfer, $request);

        $idUser = $request->getIdUser();
        if ($idUser !== null) {
            $orderTransfer->setFkCustomer($idUser);
            $customerTransfer->setIdCustomer($idUser);
            $customerTransfer = $this->customerFacade->getCustomer($customerTransfer);
        } elseif (!$customerTransfer->getPassword()) {
            $customerTransfer->setIsGuest($request->getIsGuest());
        }

        $billingAddress = $request->getBillingAddress();
        if ($billingAddress !== null) {
            $orderTransfer->setBillingAddress($billingAddress);

            $customerAddressTransfer = new AddressTransfer();
            $customerAddressTransfer->fromArray($billingAddress->toArray(), true);

            $customerTransfer->addBillingAddress($customerAddressTransfer);
        }

        $shippingAddress = $request->getShippingAddress();
        if ($shippingAddress !== null) {
            $orderTransfer->setShippingAddress($shippingAddress);

            $customerAddressTransfer = new AddressTransfer();
            $customerAddressTransfer->fromArray($shippingAddress->toArray(), true);

            $customerTransfer->addShippingAddress($customerAddressTransfer);
        }

        $orderTransfer->setCustomer($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $request
     *
     * @return void
     */
    protected function setGuestProperties(
        OrderTransfer $orderTransfer,
        CheckoutRequestTransfer $request
    ) {
        $orderTransfer->setEmail($request->getEmail());
        $orderTransfer->setFirstName($request->getBillingAddress()->getFirstName());
        $orderTransfer->setLastName($request->getBillingAddress()->getLastName());
        $orderTransfer->setSalutation($request->getBillingAddress()->getSalutation());
    }

}
