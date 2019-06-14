<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\Relationship;

use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\RestCompanyRoleAttributesTransfer;
use Spryker\Glue\CompanyRolesRestApi\CompanyRolesRestApiConfig;
use Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\Mapper\CompanyRoleMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CompanyRoleResourceRelationshipExpander implements CompanyRoleResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\Mapper\CompanyRoleMapperInterface
     */
    protected $companyRoleMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\Mapper\CompanyRoleMapperInterface $companyRoleMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        CompanyRoleMapperInterface $companyRoleMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->companyRoleMapper = $companyRoleMapper;
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

            $companyRoleCollectionTransfer = $payload->getCompanyRoleCollection();
            if ($companyRoleCollectionTransfer === null || count($companyRoleCollectionTransfer->getRoles()) === 0) {
                continue;
            }

            $this->addCompanyRoleRelationships($resource, $companyRoleCollectionTransfer);
        }

        return $resources;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     * @param \Generated\Shared\Transfer\CompanyRoleCollectionTransfer $companyRoleCollectionTransfer
     *
     * @return void
     */
    protected function addCompanyRoleRelationships(
        RestResourceInterface $resource,
        CompanyRoleCollectionTransfer $companyRoleCollectionTransfer
    ): void {
        foreach ($companyRoleCollectionTransfer->getRoles() as $companyRoleTransfer) {
            $restCompanyRoleAttributesTransfer = $this->companyRoleMapper
                ->mapCompanyRoleTransferToRestCompanyRoleAttributesTransfer(
                    $companyRoleTransfer,
                    new RestCompanyRoleAttributesTransfer()
                );

            $companyRoleResource = $this->restResourceBuilder->createRestResource(
                CompanyRolesRestApiConfig::RESOURCE_COMPANY_ROLES,
                $companyRoleTransfer->getUuid(),
                $restCompanyRoleAttributesTransfer
            );

            $resource->addRelationship($companyRoleResource);
        }
    }
}
