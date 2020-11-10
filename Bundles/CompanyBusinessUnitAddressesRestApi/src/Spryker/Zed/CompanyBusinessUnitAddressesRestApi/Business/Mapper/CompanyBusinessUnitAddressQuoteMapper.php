<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\Mapper;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\Reader\CompanyBusinessUnitAddressReaderInterface;

class CompanyBusinessUnitAddressQuoteMapper implements CompanyBusinessUnitAddressQuoteMapperInterface
{
    /**
     * @var \Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\Reader\CompanyBusinessUnitAddressReaderInterface
     */
    protected $companyBusinessUnitAddressReader;

    /**
     * @param \Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\Reader\CompanyBusinessUnitAddressReaderInterface $companyBusinessUnitAddressReader
     */
    public function __construct(CompanyBusinessUnitAddressReaderInterface $companyBusinessUnitAddressReader)
    {
        $this->companyBusinessUnitAddressReader = $companyBusinessUnitAddressReader;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapCompanyBusinessUnitAddressesToQuote(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        $quoteTransfer = $this->expandQuoteWithBillingAddress($restCheckoutRequestAttributesTransfer, $quoteTransfer);
        $quoteTransfer = $this->expandQuoteWithShippingAddress($restCheckoutRequestAttributesTransfer, $quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function expandQuoteWithBillingAddress(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        $restAddressTransfer = $restCheckoutRequestAttributesTransfer->getBillingAddress();
        if (!$restAddressTransfer || !$restAddressTransfer->getIdCompanyBusinessUnitAddress()) {
            return $quoteTransfer;
        }

        $addressTransfer = $this->companyBusinessUnitAddressReader
            ->getCompanyBusinessUnitAddress($restAddressTransfer, $quoteTransfer);

        $quoteTransfer->setBillingAddress($addressTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function expandQuoteWithShippingAddress(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        $restAddressTransfer = $restCheckoutRequestAttributesTransfer->getShippingAddress();
        if (!$restAddressTransfer || !$restAddressTransfer->getIdCompanyBusinessUnitAddress()) {
            return $quoteTransfer;
        }

        $addressTransfer = $this->companyBusinessUnitAddressReader
            ->getCompanyBusinessUnitAddress($restAddressTransfer, $quoteTransfer);

        $quoteTransfer = $this->setItemLevelShippingAddresses($quoteTransfer, $addressTransfer);

        /**
         * @deprecated Exists for Backward Compatibility reasons only.
         */
        $quoteTransfer->setShippingAddress($addressTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setItemLevelShippingAddresses(
        QuoteTransfer $quoteTransfer,
        AddressTransfer $addressTransfer
    ): QuoteTransfer {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getShipment()) {
                continue;
            }

            $itemTransfer->getShipment()->setShippingAddress($addressTransfer);
        }

        return $quoteTransfer;
    }
}
