<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Processor\CompanyBusinessUnitAddress;

use Generated\Shared\Transfer\RestCompanyBusinessUnitAddressAttributesTransfer;
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
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param string $uuid
     * @param \Generated\Shared\Transfer\RestCompanyBusinessUnitAddressAttributesTransfer $restCompanyBusinessUnitAddressAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyBusinessUnitAddressRestResponse(
        string $uuid,
        RestCompanyBusinessUnitAddressAttributesTransfer $restCompanyBusinessUnitAddressAttributesTransfer
    ): RestResponseInterface {
        return $this->restResourceBuilder->createRestResponse()
            ->addResource($this->buildRestResource($restCompanyBusinessUnitAddressAttributesTransfer, $uuid));
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
     * @param \Generated\Shared\Transfer\RestCompanyBusinessUnitAddressAttributesTransfer $restCompanyBusinessUnitAddressAttributesTransfer
     * @param string $uuid
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function buildRestResource(
        RestCompanyBusinessUnitAddressAttributesTransfer $restCompanyBusinessUnitAddressAttributesTransfer,
        string $uuid
    ): RestResourceInterface {
        $restResource = $this->restResourceBuilder->createRestResource(
            CompanyBusinessUnitAddressesRestApiConfig::RESOURCE_COMPANY_BUSINESS_UNIT_ADDRESSES,
            $uuid,
            $restCompanyBusinessUnitAddressAttributesTransfer
        );

        return $restResource;
    }
}
