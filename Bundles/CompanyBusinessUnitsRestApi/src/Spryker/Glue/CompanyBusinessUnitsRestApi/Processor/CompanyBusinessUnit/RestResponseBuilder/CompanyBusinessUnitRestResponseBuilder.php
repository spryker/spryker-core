<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\RestResponseBuilder;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CompanyBusinessUnitsRestApi\CompanyBusinessUnitsRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class CompanyBusinessUnitRestResponseBuilder implements CompanyBusinessUnitRestResponseBuilderInterface
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
     * @param string $companyBusinessUnitUuid
     * @param \Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer $restCompanyBusinessUnitAttributesTransfer
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer|null $companyBusinessUnitTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyBusinessUnitRestResponse(
        string $companyBusinessUnitUuid,
        RestCompanyBusinessUnitAttributesTransfer $restCompanyBusinessUnitAttributesTransfer,
        ?CompanyBusinessUnitTransfer $companyBusinessUnitTransfer = null
    ): RestResponseInterface {
        $companyBusinessUnitRestResource = $this->buildCompanyBusinessUnitRestResource(
            $companyBusinessUnitUuid,
            $restCompanyBusinessUnitAttributesTransfer,
            $companyBusinessUnitTransfer
        );

        return $this->restResourceBuilder->createRestResponse()
            ->addResource($companyBusinessUnitRestResource);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyBusinessUnitIdMissingError(): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CompanyBusinessUnitsRestApiConfig::RESPONSE_CODE_COMPANY_BUSINESS_UNIT_ID_IS_MISSING)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(CompanyBusinessUnitsRestApiConfig::RESPONSE_DETAIL_COMPANY_BUSINESS_UNIT_ID_IS_MISSING);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyBusinessUnitNotFoundError(): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CompanyBusinessUnitsRestApiConfig::RESPONSE_CODE_COMPANY_BUSINESS_UNIT_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(CompanyBusinessUnitsRestApiConfig::RESPONSE_DETAIL_COMPANY_BUSINESS_UNIT_NOT_FOUND);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorTransfer);
    }

    /**
     * @param string $companyBusinessUnitUuid
     * @param \Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer $restCompanyBusinessUnitAttributesTransfer
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer|null $companyBusinessUnitTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function buildCompanyBusinessUnitRestResource(
        string $companyBusinessUnitUuid,
        RestCompanyBusinessUnitAttributesTransfer $restCompanyBusinessUnitAttributesTransfer,
        ?CompanyBusinessUnitTransfer $companyBusinessUnitTransfer = null
    ): RestResourceInterface {
        $restResource = $this->restResourceBuilder->createRestResource(
            CompanyBusinessUnitsRestApiConfig::RESOURCE_COMPANY_BUSINESS_UNITS,
            $companyBusinessUnitUuid,
            $restCompanyBusinessUnitAttributesTransfer
        );

        if (!$companyBusinessUnitTransfer) {
            return $restResource;
        }

        $restResource->setPayload($companyBusinessUnitTransfer);

        return $restResource;
    }
}
