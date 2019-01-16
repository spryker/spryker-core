<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUnitAddressesRestApi\Processor\CompanyUnitAddress;

use Generated\Shared\Transfer\RestCompanyUnitAddressAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CompanyUnitAddressesRestApi\CompanyUnitAddressesRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class CompanyUnitAddressRestResponseBuilder implements CompanyUnitAddressRestResponseBuilderInterface
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
     * @param \Generated\Shared\Transfer\RestCompanyUnitAddressAttributesTransfer $restCompanyUnitAddressAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyUnitAddressRestResponse(
        string $uuid,
        RestCompanyUnitAddressAttributesTransfer $restCompanyUnitAddressAttributesTransfer
    ): RestResponseInterface {
        return $this->restResourceBuilder->createRestResponse()
            ->addResource($this->buildRestResource($restCompanyUnitAddressAttributesTransfer, $uuid));
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyUnitAddressIdMissingError(): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CompanyUnitAddressesRestApiConfig::RESPONSE_CODE_COMPANY_UNIT_ADDRESS_ID_IS_MISSING)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(CompanyUnitAddressesRestApiConfig::RESPONSE_DETAIL_COMPANY_UNIT_ADDRESS_ID_IS_MISSING);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyUnitAddressNotFoundError(): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CompanyUnitAddressesRestApiConfig::RESPONSE_CODE_COMPANY_UNIT_ADDRESS_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(CompanyUnitAddressesRestApiConfig::RESPONSE_DETAIL_COMPANY_UNIT_ADDRESS_NOT_FOUND);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCompanyUnitAddressAttributesTransfer $restCompanyUnitAddressAttributesTransfer
     * @param string $uuid
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function buildRestResource(
        RestCompanyUnitAddressAttributesTransfer $restCompanyUnitAddressAttributesTransfer,
        string $uuid
    ): RestResourceInterface {
        $restResource = $this->restResourceBuilder->createRestResource(
            CompanyUnitAddressesRestApiConfig::RESOURCE_COMPANY_UNIT_ADDRESSES,
            $uuid,
            $restCompanyUnitAddressAttributesTransfer
        );

        return $restResource;
    }
}
