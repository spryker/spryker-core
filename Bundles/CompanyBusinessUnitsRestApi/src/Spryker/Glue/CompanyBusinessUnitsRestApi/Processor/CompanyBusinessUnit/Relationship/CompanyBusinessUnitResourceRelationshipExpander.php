<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\Relationship;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\RestCompanyBusinessUnitAttributesTransfer;
use Spryker\Glue\CompanyBusinessUnitsRestApi\CompanyBusinessUnitsRestApiConfig;
use Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\CompanyBusinessUnit\Mapper\CompanyBusinessUnitMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CompanyBusinessUnitResourceRelationshipExpander implements CompanyBusinessUnitResourceRelationshipExpanderInterface
{
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
            if ($companyBusinessUnitTransfer === null) {
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

            $resource->addRelationship($companyBusinessUnitResource);
        }

        return $resources;
    }
}
