<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Processor\CompanyBusinessUnitAddress\RestResponseBuilder;

use Generated\Shared\Transfer\RestCompanyBusinessUnitAddressesAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CompanyBusinessUnitAddressesRestApi\CompanyBusinessUnitAddressesRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class CompanyBusinessUnitAddressRestResponseBuilder implements CompanyBusinessUnitAddressRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder)
    {
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param string $companyBusinessUnitAddressUuid
     * @param \Generated\Shared\Transfer\RestCompanyBusinessUnitAddressesAttributesTransfer $restCompanyBusinessUnitAddressesAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyBusinessUnitAddressRestResponse(
        string $companyBusinessUnitAddressUuid,
        RestCompanyBusinessUnitAddressesAttributesTransfer $restCompanyBusinessUnitAddressesAttributesTransfer
    ): RestResponseInterface {
        return $this->restResourceBuilder->createRestResponse()
            ->addResource($this->createCompanyBusinessUnitAddressRestResource($companyBusinessUnitAddressUuid, $restCompanyBusinessUnitAddressesAttributesTransfer));
    }

    /**
     * @param string $companyBusinessUnitAddressUuid
     * @param \Generated\Shared\Transfer\RestCompanyBusinessUnitAddressesAttributesTransfer $restCompanyBusinessUnitAddressesAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createCompanyBusinessUnitAddressRestResource(
        string $companyBusinessUnitAddressUuid,
        RestCompanyBusinessUnitAddressesAttributesTransfer $restCompanyBusinessUnitAddressesAttributesTransfer
    ): RestResourceInterface {
        $restResource = $this->restResourceBuilder->createRestResource(
            CompanyBusinessUnitAddressesRestApiConfig::RESOURCE_COMPANY_BUSINESS_UNIT_ADDRESSES,
            $companyBusinessUnitAddressUuid,
            $restCompanyBusinessUnitAddressesAttributesTransfer
        );

        return $restResource;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyBusinessUnitAddressIdMissingError(): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CompanyBusinessUnitAddressesRestApiConfig::RESPONSE_CODE_COMPANY_BUSINESS_UNIT_ADDRESS_ID_IS_MISSING)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(CompanyBusinessUnitAddressesRestApiConfig::RESPONSE_DETAIL_COMPANY_BUSINESS_UNIT_ADDRESS_ID_IS_MISSING);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyBusinessUnitAddressNotFoundError(): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CompanyBusinessUnitAddressesRestApiConfig::RESPONSE_CODE_COMPANY_BUSINESS_UNIT_ADDRESS_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(CompanyBusinessUnitAddressesRestApiConfig::RESPONSE_DETAIL_COMPANY_BUSINESS_UNIT_ADDRESS_NOT_FOUND);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createResourceNotImplementedError(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setStatus(Response::HTTP_NOT_IMPLEMENTED)
            ->setDetail(CompanyBusinessUnitAddressesRestApiConfig::RESPONSE_DETAIL_RESOURCE_NOT_IMPLEMENTED);

        return $this->restResourceBuilder
            ->createRestResponse()
            ->addError($restErrorMessageTransfer);
    }
}
