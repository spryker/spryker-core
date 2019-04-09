<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompaniesRestApi\Processor\Company\Relationship;

use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\RestCompaniesAttributesTransfer;
use Spryker\Glue\CompaniesRestApi\CompaniesRestApiConfig;
use Spryker\Glue\CompaniesRestApi\Processor\Company\Mapper\CompanyMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class CompanyResourceRelationshipExpander implements CompanyResourceRelationshipExpanderInterface
{
    protected const PROPERTY_COMPANY = 'company';

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CompaniesRestApi\Processor\Company\Mapper\CompanyMapperInterface
     */
    protected $companyMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CompaniesRestApi\Processor\Company\Mapper\CompanyMapperInterface $companyMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        CompanyMapperInterface $companyMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->companyMapper = $companyMapper;
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
             * @var \Generated\Shared\Transfer\CompanyUserTransfer|null $payload
             */
            $payload = $resource->getPayload();
            if ($payload === null || !($payload instanceof CompanyUserTransfer)) {
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
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addResourceRelationshipsByPayload(array $resources, RestRequestInterface $restRequest): array
    {
        foreach ($resources as $resource) {
            /**
             * @var \Generated\Shared\Transfer\CompanyUserTransfer|\Generated\Shared\Transfer\CompanyRoleTransfer|\Generated\Shared\Transfer\CompanyBusinessUnitTransfer|null $payload
             */
            $payload = $resource->getPayload();

            if (!$this->isValidPayload($payload)) {
                continue;
            }

            $companyTransfer = $payload->offsetGet(static::PROPERTY_COMPANY);

            $resource->addRelationship($this->createCompanyResource($companyTransfer));
        }

        return $resources;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $payload
     *
     * @return bool
     */
    protected function isValidPayload(?AbstractTransfer $payload = null): bool
    {
        return $payload && $this->isCompanyTransferProvidedInPayload($payload);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $payload
     *
     * @return bool
     */
    protected function isCompanyTransferProvidedInPayload(AbstractTransfer $payload): bool
    {
        return $payload->offsetExists(static::PROPERTY_COMPANY) && $payload->offsetGet(static::PROPERTY_COMPANY) instanceof CompanyTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createCompanyResource(CompanyTransfer $companyTransfer): RestResourceInterface
    {
        $restCompaniesAttributesTransfer = $this->companyMapper
            ->mapCompanyTransferToRestCompaniesAttributesTransfer(
                $companyTransfer,
                new RestCompaniesAttributesTransfer()
            );

        return $this->restResourceBuilder->createRestResource(
            CompaniesRestApiConfig::RESOURCE_COMPANIES,
            $companyTransfer->getUuid(),
            $restCompaniesAttributesTransfer
        );
    }
}
