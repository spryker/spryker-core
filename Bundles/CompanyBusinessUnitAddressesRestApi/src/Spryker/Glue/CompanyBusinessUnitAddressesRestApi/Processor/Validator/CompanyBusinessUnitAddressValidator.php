<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Processor\Validator;

use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CompanyBusinessUnitAddressesRestApi\CompanyBusinessUnitAddressesRestApiConfig;
use Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Dependency\Client\CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressClientInterface;
use Symfony\Component\HttpFoundation\Response;

class CompanyBusinessUnitAddressValidator implements CompanyBusinessUnitAddressValidatorInterface
{
    /**
     * @var \Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Dependency\Client\CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressClientInterface
     */
    protected $companyUnitAddressClient;

    /**
     * @param \Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Dependency\Client\CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressClientInterface $companyUnitAddressClient
     */
    public function __construct(CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressClientInterface $companyUnitAddressClient)
    {
        $this->companyUnitAddressClient = $companyUnitAddressClient;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    public function validateCompanyBusinessUnitAddresses(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
    ): RestErrorCollectionTransfer {
        $restErrorCollectionTransfer = new RestErrorCollectionTransfer();
        $shippingAddressUuids = $this->extractShippingAddressUuids($restCheckoutRequestAttributesTransfer);

        if (!$shippingAddressUuids) {
            return $restErrorCollectionTransfer;
        }

        if (!$this->isCompanyUserAccount($restCheckoutRequestAttributesTransfer)) {
            return $this->buildErrorMessage(
                CompanyBusinessUnitAddressesRestApiConfig::RESPONSE_DETAILS_COMPANY_ADDRESSES_APPLICABLE_FOR_COMPANY_USERS_ONLY,
                CompanyBusinessUnitAddressesRestApiConfig::RESPONSE_CODE_COMPANY_ADDRESSES_APPLICABLE_FOR_COMPANY_USERS_ONLY
            );
        }

        $companyShippingAddressUuids = $this->getCompanyShippingAddressUuids($restCheckoutRequestAttributesTransfer);

        foreach ($shippingAddressUuids as $shippingAddressUuid) {
            if (!in_array($shippingAddressUuid, $companyShippingAddressUuids, true)) {
                return $this->buildErrorMessage(
                    sprintf(CompanyBusinessUnitAddressesRestApiConfig::RESPONSE_DETAILS_COMPANY_ADDRESS_IN_CHECKOUT_DATA_NOT_FOUND, $shippingAddressUuid),
                    CompanyBusinessUnitAddressesRestApiConfig::RESPONSE_CODE_COMPANY_ADDRESS_IN_CHECKOUT_DATA_NOT_FOUND
                );
            }
        }

        return new RestErrorCollectionTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return bool
     */
    protected function isCompanyUserAccount(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): bool
    {
        return $restCheckoutRequestAttributesTransfer->getRestUser() && $restCheckoutRequestAttributesTransfer->getRestUser()->getIdCompany();
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return string[]
     */
    protected function getCompanyShippingAddressUuids(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): array
    {
        $idCompany = $restCheckoutRequestAttributesTransfer
            ->requireRestUser()
            ->getRestUser()
                ->requireIdCompany()
                ->getIdCompany();

        $companyUnitAddressCriteriaFilterTransfer = (new CompanyUnitAddressCriteriaFilterTransfer())
            ->setIdCompany($idCompany);

        $companyShippingAddressUuids = [];
        $companyUnitAddressTransfers = $this->companyUnitAddressClient
            ->getCompanyUnitAddressCollection($companyUnitAddressCriteriaFilterTransfer)
            ->getCompanyUnitAddresses();

        foreach ($companyUnitAddressTransfers as $companyUnitAddressTransfer) {
            $companyShippingAddressUuids[] = $companyUnitAddressTransfer->getUuid();
        }

        return $companyShippingAddressUuids;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     *
     * @return string[]
     */
    protected function extractShippingAddressUuids(RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer): array
    {
        $shippingAddressUuids = [];

        foreach ($restCheckoutRequestAttributesTransfer->getShipments() as $restShipmentsTransfer) {
            $restAddressTransfer = $restShipmentsTransfer->getShippingAddress();

            if ($restAddressTransfer && $restAddressTransfer->getIdCompanyBusinessUnitAddress()) {
                $shippingAddressUuids[] = $restAddressTransfer->getIdCompanyBusinessUnitAddress();
            }
        }

        return $shippingAddressUuids;
    }

    /**
     * @param string $detail
     * @param string $code
     * @param int|null $status
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    protected function buildErrorMessage(
        string $detail,
        string $code,
        ?int $status = Response::HTTP_NOT_FOUND
    ): RestErrorCollectionTransfer {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setDetail($detail)
            ->setCode($code)
            ->setStatus($status);

        return (new RestErrorCollectionTransfer())
            ->addRestError($restErrorMessageTransfer);
    }
}
