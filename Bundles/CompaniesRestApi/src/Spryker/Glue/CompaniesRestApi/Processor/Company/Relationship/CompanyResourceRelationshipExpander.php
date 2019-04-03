<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompaniesRestApi\Processor\Company\Relationship;

use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\RestCompanyAttributesTransfer;
use Spryker\Glue\CompaniesRestApi\CompaniesRestApiConfig;
use Spryker\Glue\CompaniesRestApi\Processor\Company\Mapper\CompanyMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class CompanyResourceRelationshipExpander implements CompanyResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CompaniesRestApi\Processor\Company\Mapper\CompanyMapperInterface
     */
    protected $companyMapper;

    /**
     * @var \Spryker\Glue\CompaniesRestApi\CompaniesRestApiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CompaniesRestApi\Processor\Company\Mapper\CompanyMapperInterface $companyMapper
     * @param \Spryker\Glue\CompaniesRestApi\CompaniesRestApiConfig $config
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        CompanyMapperInterface $companyMapper,
        CompaniesRestApiConfig $config
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->companyMapper = $companyMapper;
        $this->config = $config;
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
            /**
             * @var \Generated\Shared\Transfer\CompanyUserTransfer|\Generated\Shared\Transfer\CompanyRoleTransfer|\Generated\Shared\Transfer\CompanyBusinessUnitTransfer|null $payload
             */
            $payload = $resource->getPayload();

            if (!$payload || !$this->isValidPayloadType($payload)) {
                continue;
            }

            $companyTransfer = $payload->getCompany();
            if ($companyTransfer === null) {
                continue;
            }

            $resource->addRelationship($this->createCompanyResource($companyTransfer));
        }

        return $resources;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createCompanyResource(CompanyTransfer $companyTransfer): RestResourceInterface
    {
        $restCompanyAttributesTransfer = $this->companyMapper
            ->mapCompanyTransferToRestCompanyAttributesTransfer(
                $companyTransfer,
                new RestCompanyAttributesTransfer()
            );

        return $this->restResourceBuilder->createRestResource(
            CompaniesRestApiConfig::RESOURCE_COMPANIES,
            $companyTransfer->getUuid(),
            $restCompanyAttributesTransfer
        );
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $payload
     *
     * @return bool
     */
    protected function isValidPayloadType(AbstractTransfer $payload): bool
    {
        foreach ($this->config->getExtendableResourceTransfers() as $extendableResourceTransfer) {
            if ($payload instanceof $extendableResourceTransfer) {
                return true;
            }
        }

        return false;
    }
}
