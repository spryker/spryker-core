<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\Relationship;

use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\RestCompanyRoleAttributesTransfer;
use Spryker\Glue\CompanyRolesRestApi\CompanyRolesRestApiConfig;
use Spryker\Glue\CompanyRolesRestApi\Processor\CompanyRole\Mapper\CompanyRoleMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CompanyRoleResourceRelationshipExpander implements CompanyRoleResourceRelationshipExpanderInterface
{
    protected const PATTERN_COMPANY_ROLE_RESOURCE_SELF_LINK = '%s/%s/%s/%s';

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
            $payload = $resource->getPayload();
            if ($payload === null || $payload instanceof CompanyUserTransfer === false) {
                continue;
            }

            $companyRoleCollectionTransfer = $payload->getCompanyRoleCollection();
            $companyTransfer = $payload->getCompany();
            if ($companyRoleCollectionTransfer === null || $companyTransfer === null || count($companyRoleCollectionTransfer->getRoles()) === 0) {
                continue;
            }

            $this->addCompanyRoleRelationships($resource, $companyTransfer, $companyRoleCollectionTransfer);
        }

        return $resources;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     * @param \Generated\Shared\Transfer\CompanyRoleCollectionTransfer $companyRoleCollectionTransfer
     *
     * @return void
     */
    protected function addCompanyRoleRelationships(
        RestResourceInterface $resource,
        CompanyTransfer $companyTransfer,
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

            $companyRoleResource->addLink(
                RestLinkInterface::LINK_SELF,
                $this->createSelfLink($companyTransfer, $companyRoleTransfer)
            );

            $resource->addRelationship($companyRoleResource);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return string
     */
    protected function createSelfLink(
        CompanyTransfer $companyTransfer,
        CompanyRoleTransfer $companyRoleTransfer
    ): string {
        return sprintf(
            static::PATTERN_COMPANY_ROLE_RESOURCE_SELF_LINK,
            CompanyRolesRestApiConfig::RESOURCE_COMPANIES,
            $companyTransfer->getUuid(),
            CompanyRolesRestApiConfig::RESOURCE_COMPANY_ROLES,
            $companyRoleTransfer->getUuid()
        );
    }
}
