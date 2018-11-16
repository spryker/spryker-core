<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Business\Checkout\Mapper\Customer;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
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
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteWithCustomerData(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        $restCustomerTransfer = $restCheckoutRequestAttributesTransfer->getCart()->getCustomer();

        $customerResponseTransfer = $this->customerFacade->findCustomerByReference($restCustomerTransfer->getCustomerReference());

        if ($customerResponseTransfer->getHasCustomer() === false) {
            $customerTransfer = (new CustomerTransfer())
                ->fromArray($restCustomerTransfer->toArray(), true)
                ->setIsGuest(true);

            return $quoteTransfer->setCustomer($customerTransfer);
        }

        $quoteTransfer
            ->setCustomerReference($customerResponseTransfer->getCustomerTransfer()->getCustomerReference())
            ->setCustomer($customerResponseTransfer->getCustomerTransfer());

        $quoteTransfer = $this->expandQuoteWithCustomerAddresses(
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
    protected function expandQuoteWithCustomerAddresses(QuoteTransfer $quoteTransfer, CustomerTransfer $customerTransfer): QuoteTransfer
    {
        if ($quoteTransfer->getBillingAddress() !== null) {
            $quoteTransfer->setBillingAddress(
                $this->getAddressByUuid($quoteTransfer->getBillingAddress(), $customerTransfer)
            );
        }

        if ($quoteTransfer->getShippingAddress() !== null) {
            $quoteTransfer->setShippingAddress(
                $this->getAddressByUuid($quoteTransfer->getShippingAddress(), $customerTransfer)
            );
        }

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
            if ($customerAddressTransfer->getUuid() !== $addressTransfer->getUuid()) {
                continue;
            }

            return $customerAddressTransfer;
        }

        return $addressTransfer;
    }
}
