<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\Relationship;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer;
use Spryker\Glue\CompanyBusinessUnitsRestApi\CompanyBusinessUnitsRestApiConfig;
use Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\Mapper\CompanyBusinessUnitMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CompanyBusinessUnitResourceRelationshipExpander implements CompanyBusinessUnitResourceRelationshipExpanderInterface
{
    protected const PATTERN_COMPANY_BUSINESS_UNIT_RESOURCE_SELF_LINK = '%s/%s/%s/%s';

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\Mapper\CompanyBusinessUnitMapperInterface
     */
    protected $companyBusinessUnitMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\Mapper\CompanyBusinessUnitMapperInterface $companyBusinessUnitMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        CompanyBusinessUnitMapperInterface $companyBusinessUnitMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->companyBusinessUnitMapper = $companyBusinessUnitMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): array
    {
        foreach ($resources as $resource) {
            $payload = $resource->getPayload();
            if ($payload === null || $payload instanceof CompanyUserTransfer === false) {
                continue;
            }

            $companyBusinessUnitTransfer = $payload->getCompanyBusinessUnit();
            $companyTransfer = $payload->getCompany();
            if ($companyBusinessUnitTransfer === null || $companyTransfer === null) {
                continue;
            }

            $restCompanyBusinessUnitAttributesTransfer = $this->companyBusinessUnitMapper
                ->mapCompanyBusinessUnitTransferToRestCompanyBusinessUnitAttributesTransfer(
                    $companyBusinessUnitTransfer,
                    new RestCompanyBusinessUnitAttributesTransfer()
                );

            $companyBusinessUnitResource = $this->restResourceBuilder->createRestResource(
                CompanyBusinessUnitsRestApiConfig::RESOURCE_COMPANY_BUSINESS_UNITS,
                $companyBusinessUnitTransfer->getUuid(),
                $restCompanyBusinessUnitAttributesTransfer
            );

            $companyBusinessUnitResource->addLink(
                RestLinkInterface::LINK_SELF,
                $this->createSelfLink($companyTransfer, $companyBusinessUnitTransfer)
            );

            $resource->addRelationship($companyBusinessUnitResource);
        }

        return $resources;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return string
     */
    protected function createSelfLink(
        CompanyTransfer $companyTransfer,
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): string {
        return sprintf(
            static::PATTERN_COMPANY_BUSINESS_UNIT_RESOURCE_SELF_LINK,
            CompanyBusinessUnitsRestApiConfig::RESOURCE_COMPANIES,
            $companyTransfer->getUuid(),
            CompanyBusinessUnitsRestApiConfig::RESOURCE_COMPANY_BUSINESS_UNITS,
            $companyBusinessUnitTransfer->getUuid()
        );
    }
}
