<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\Mapper;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestAddressTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Dependency\Facade\CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressFacadeInterface;

class CompanyBusinessUnitAddressQuoteMapper implements CompanyBusinessUnitAddressQuoteMapperInterface
{
    /**
     * @var \Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Dependency\Facade\CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressFacadeInterface
     */
    protected $companyUnitAddressFacade;

    /**
     * @param \Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Dependency\Facade\CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressFacadeInterface $companyUnitAddressFacade
     */
    public function __construct(CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressFacadeInterface $companyUnitAddressFacade)
    {
        $this->companyUnitAddressFacade = $companyUnitAddressFacade;
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
        if (!$restAddressTransfer || !$restAddressTransfer->getCompanyBusinessUnitAddressId()) {
            return $quoteTransfer;
        }

        $quoteTransfer->setBillingAddress(
            $this->createAddressTransfer($restAddressTransfer, $quoteTransfer)
        );

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
        if (!$restAddressTransfer || !$restAddressTransfer->getCompanyBusinessUnitAddressId()) {
            return $quoteTransfer;
        }

        $addressTransfer = $this->createAddressTransfer($restAddressTransfer, $quoteTransfer);
        $quoteTransfer = $this->setItemLevelShippingAddresses($quoteTransfer, $addressTransfer);

        /**
         * @deprecated Exists for Backward Compatibility reasons only.
         */
        $quoteTransfer->setShippingAddress($addressTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestAddressTransfer $restAddressTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function createAddressTransfer(
        RestAddressTransfer $restAddressTransfer,
        QuoteTransfer $quoteTransfer
    ): AddressTransfer {
        $companyUnitAddressResponseTransfer = $this->companyUnitAddressFacade->findCompanyBusinessUnitAddressByUuid(
            (new CompanyUnitAddressTransfer())->setUuid($restAddressTransfer->getCompanyBusinessUnitAddressId())
        );

        if (
            !$companyUnitAddressResponseTransfer->getIsSuccessful()
            || !$companyUnitAddressResponseTransfer->getCompanyUnitAddressTransfer()
        ) {
            return (new AddressTransfer())->fromArray($restAddressTransfer->toArray(), true);
        }

        return (new AddressTransfer())
            ->fromArray($companyUnitAddressResponseTransfer->getCompanyUnitAddressTransfer()->toArray(), true)
            ->setIsAddressSavingSkipped(true)
            ->setEmail($quoteTransfer->getCustomer()->getEmail())
            ->setFirstName($quoteTransfer->getCustomer()->getFirstName())
            ->setLastName($quoteTransfer->getCustomer()->getLastName())
            ->setSalutation($quoteTransfer->getCustomer()->getSalutation())
            ->setCompany($companyUnitAddressResponseTransfer->getCompanyUnitAddressTransfer()->getCompany()->getName());
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
