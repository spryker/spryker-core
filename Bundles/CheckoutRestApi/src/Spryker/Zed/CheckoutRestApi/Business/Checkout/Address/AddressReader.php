<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Business\Checkout\Address;

use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCustomerFacadeInterface;

class AddressReader implements AddressReaderInterface
{
    /**
     * @var \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCustomerFacadeInterface $customerFacade
     */
    public function __construct(CheckoutRestApiToCustomerFacadeInterface $customerFacade)
    {
        $this->customerFacade = $customerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AddressesTransfer
     */
    public function getAddressesTransfer(QuoteTransfer $quoteTransfer): AddressesTransfer
    {
        $customerTransfer = $quoteTransfer->getCustomer();
        if (!$customerTransfer || $customerTransfer->getIsGuest()) {
            return new AddressesTransfer();
        }

        $customerResponseTransfer = $this->customerFacade->findCustomerByReference($customerTransfer->getCustomerReference());
        if (!$customerResponseTransfer->getHasCustomer()) {
            return new AddressesTransfer();
        }

        return $this->extendAddressesWithDefaultBillingAndShipping($customerResponseTransfer->getCustomerTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\AddressesTransfer
     */
    protected function extendAddressesWithDefaultBillingAndShipping(CustomerTransfer $customerTransfer): AddressesTransfer
    {
        $addressesTransfer = new AddressesTransfer();
        foreach ($customerTransfer->getAddresses()->getAddresses() as $addressKey => $addressTransfer) {
            $addressTransfer->setIsDefaultShipping(
                $addressTransfer->getIdCustomerAddress() === (int)$customerTransfer->getDefaultShippingAddress()
            );
            $addressTransfer->setIsDefaultBilling(
                $addressTransfer->getIdCustomerAddress() === (int)$customerTransfer->getDefaultBillingAddress()
            );

            $addressesTransfer->addAddress($addressTransfer);
        }

        return $addressesTransfer;
    }
}
