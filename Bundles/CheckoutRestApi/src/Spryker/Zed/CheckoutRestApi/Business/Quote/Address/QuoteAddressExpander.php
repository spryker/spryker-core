<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutRestApi\Business\Quote\Address;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCustomersRestApiFacadeInterface;

class QuoteAddressExpander implements QuoteAddressExpanderInterface
{
    /**
     * @var \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCustomersRestApiFacadeInterface
     */
    protected $customersRestApiFacade;

    /**
     * @param \Spryker\Zed\CheckoutRestApi\Dependency\Facade\CheckoutRestApiToCustomersRestApiFacadeInterface $customersRestApiFacade
     */
    public function __construct(CheckoutRestApiToCustomersRestApiFacadeInterface $customersRestApiFacade)
    {
        $this->customersRestApiFacade = $customersRestApiFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteAddressesWithCustomerAddressByUuid(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if (!$quoteTransfer->getCustomer()->getIdCustomer()) {
            return $quoteTransfer;
        }

        $quoteTransfer->setBillingAddress(
            $this->getAddressTransferExpandedWithCustomerAddress(
                $quoteTransfer->getBillingAddress(),
                $quoteTransfer->getCustomer()->getIdCustomer()
            )
        );
        $quoteTransfer->setShippingAddress(
            $this->getAddressTransferExpandedWithCustomerAddress(
                $quoteTransfer->getShippingAddress(),
                $quoteTransfer->getCustomer()->getIdCustomer()
            )
        );

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function getAddressTransferExpandedWithCustomerAddress(AddressTransfer $addressTransfer, int $idCustomer): AddressTransfer
    {
        if ($addressTransfer->getUuid() === null) {
            return $addressTransfer;
        }

        $foundAddressTransfer = $this->customersRestApiFacade->findCustomerAddressByUuid(
            $addressTransfer->getUuid(),
            $idCustomer
        );

        return ($foundAddressTransfer !== null) ? $foundAddressTransfer : $addressTransfer->setUuid(null);
    }
}
