<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompaniesRestApi\Processor\Company\Relationship;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\RestCompanyAttributesTransfer;
use Spryker\Glue\CompaniesRestApi\CompaniesRestApiConfig;
use Spryker\Glue\CompaniesRestApi\Processor\Company\Mapper\CompanyMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CompanyResourceRelationshipByCompanyRoleExpander implements CompanyResourceRelationshipByCompanyRoleExpanderInterface
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
             * @var \Generated\Shared\Transfer\CompanyRoleTransfer|null $payload
             */
            $payload = $resource->getPayload();
            if (!$payload || !($payload instanceof CompanyRoleTransfer)) {
                continue;
            }

            $companyTransfer = $payload->getCompany();
            if ($companyTransfer === null) {
                continue;
            }

            $restCompanyAttributesTransfer = $this->companyMapper
                ->mapCompanyTransferToRestCompanyAttributesTransfer(
                    $companyTransfer,
                    new RestCompanyAttributesTransfer()
                );

            $companyResource = $this->restResourceBuilder->createRestResource(
                CompaniesRestApiConfig::RESOURCE_COMPANIES,
                $companyTransfer->getUuid(),
                $restCompanyAttributesTransfer
            );

            $resource->addRelationship($companyResource);
        }

        return $resources;
    }
}
