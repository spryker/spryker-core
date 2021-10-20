<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\Validator;

use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Dependency\Facade\CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressFacadeInterface;

class CompanyBusinessUnitAddressValidator implements CompanyBusinessUnitAddressValidatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_PARAMETER_ID = '%id%';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_COMPANY_ADDRESS_IN_CHECKOUT_DATA_NOT_FOUND = 'checkout.validation.company_address.not_found';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_COMPANY_ADDRESSES_APPLICABLE_FOR_COMPANY_USERS_ONLY = 'checkout.validation.company_address.not_applicable';

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
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function validateCompanyBusinessUnitAddressesInCheckoutData(
        CheckoutDataTransfer $checkoutDataTransfer
    ): CheckoutResponseTransfer {
        $checkoutResponseTransfer = (new CheckoutResponseTransfer())->setIsSuccess(true);
        $shippingAddressUuids = $this->extractShippingAddressUuids($checkoutDataTransfer);

        if (!$shippingAddressUuids) {
            return $checkoutResponseTransfer;
        }

        if (!$this->isCompanyUserAccount($checkoutDataTransfer)) {
            return $this->getErrorResponse(
                $checkoutResponseTransfer,
                static::GLOSSARY_KEY_COMPANY_ADDRESSES_APPLICABLE_FOR_COMPANY_USERS_ONLY,
            );
        }

        $companyShippingAddressUuids = $this->getCompanyUnitAddressUuids($checkoutDataTransfer);

        foreach ($shippingAddressUuids as $shippingAddressUuid) {
            if (!in_array($shippingAddressUuid, $companyShippingAddressUuids, true)) {
                $checkoutResponseTransfer = $this->getErrorResponse(
                    $checkoutResponseTransfer,
                    static::GLOSSARY_KEY_COMPANY_ADDRESS_IN_CHECKOUT_DATA_NOT_FOUND,
                    [static::GLOSSARY_PARAMETER_ID => $shippingAddressUuid],
                );
            }
        }

        return $checkoutResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return bool
     */
    protected function isCompanyUserAccount(CheckoutDataTransfer $checkoutDataTransfer): bool
    {
        return $checkoutDataTransfer->getQuote()
            && $checkoutDataTransfer->getQuote()->getCustomer()
            && $checkoutDataTransfer->getQuote()->getCustomer()->getCompanyUserTransfer()
            && $checkoutDataTransfer->getQuote()->getCustomer()->getCompanyUserTransfer()->getFkCompany();
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return array<string>
     */
    protected function getCompanyUnitAddressUuids(CheckoutDataTransfer $checkoutDataTransfer): array
    {
        $companyUnitAddressCriteriaFilterTransfer = (new CompanyUnitAddressCriteriaFilterTransfer())
            ->setIdCompany($checkoutDataTransfer->getQuote()->getCustomer()->getCompanyUserTransfer()->getFkCompany());

        $companyUnitAddressUuids = [];
        $companyUnitAddressTransfers = $this->companyUnitAddressFacade
            ->getCompanyUnitAddressCollection($companyUnitAddressCriteriaFilterTransfer)
            ->getCompanyUnitAddresses();

        foreach ($companyUnitAddressTransfers as $companyUnitAddressTransfer) {
            $companyUnitAddressUuids[] = $companyUnitAddressTransfer->getUuid();
        }

        return $companyUnitAddressUuids;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer
     *
     * @return array<string>
     */
    protected function extractShippingAddressUuids(CheckoutDataTransfer $checkoutDataTransfer): array
    {
        $shippingAddressUuids = [];

        foreach ($checkoutDataTransfer->getShipments() as $restShipmentsTransfer) {
            $restAddressTransfer = $restShipmentsTransfer->getShippingAddress();

            if ($restAddressTransfer && $restAddressTransfer->getIdCompanyBusinessUnitAddress()) {
                $shippingAddressUuids[] = $restAddressTransfer->getIdCompanyBusinessUnitAddress();
            }
        }

        return $shippingAddressUuids;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param string $message
     * @param array<string> $parameters
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function getErrorResponse(
        CheckoutResponseTransfer $checkoutResponseTransfer,
        string $message,
        array $parameters = []
    ): CheckoutResponseTransfer {
        return $checkoutResponseTransfer
            ->setIsSuccess(false)
            ->addError(
                (new CheckoutErrorTransfer())
                    ->setMessage($message)
                    ->setParameters($parameters),
            );
    }
}
