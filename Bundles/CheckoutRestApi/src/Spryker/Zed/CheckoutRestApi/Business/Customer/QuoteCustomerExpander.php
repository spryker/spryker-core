<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Business\Customer;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCustomerFacadeInterface;

class QuoteCustomerExpander implements QuoteCustomerExpanderInterface
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
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteTransferWithCustomerTransfer(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $customerTransfer = $quoteTransfer->getCustomer();

        if ($customerTransfer === null
            || $customerTransfer->getCustomerReference() === null
            || $customerTransfer->getIdCustomer() === null) {
            return $quoteTransfer;
        }

        $customerResponseTransfer = $this->customerFacade->findCustomerByReference($customerTransfer->getCustomerReference());

        if ($customerResponseTransfer->getIsSuccess() === false) {
            return $quoteTransfer;
        }

        $quoteTransfer->setCustomer(
            $customerResponseTransfer->getCustomerTransfer()
        );

        $quoteTransfer = $this->expandQuoteTransferWithCustomerAddresses(
            $quoteTransfer,
            $customerResponseTransfer->getCustomerTransfer()
        );

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function expandQuoteTransferWithCustomerAddresses(QuoteTransfer $quoteTransfer, CustomerTransfer $customerTransfer): QuoteTransfer
    {
        $quoteTransfer->setBillingAddress(
            $this->getAddressByUuid($quoteTransfer->getBillingAddress(), $customerTransfer)
        );

        $quoteTransfer->setShippingAddress(
            $this->getAddressByUuid($quoteTransfer->getShippingAddress(), $customerTransfer)
        );

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function getAddressByUuid(AddressTransfer $addressTransfer, CustomerTransfer $customerTransfer): AddressTransfer
    {
        if ($addressTransfer->getUuid() === null) {
            return $addressTransfer;
        }

        foreach ($customerTransfer->getAddresses()->getAddresses() as $customerAddressTransfer) {
            if ($customerAddressTransfer->getUuid() === null
                || $customerAddressTransfer->getUuid() !== $addressTransfer->getUuid()) {
                continue;
            }

            return $customerAddressTransfer;
        }

        return $addressTransfer;
    }
}
