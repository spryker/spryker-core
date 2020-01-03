<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUsersRestApi\Processor\CompanyUser\Relationship;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\RestCompanyUserAttributesTransfer;
use Spryker\Glue\CompanyUsersRestApi\Processor\Mapper\CompanyUserMapperInterface;
use Spryker\Glue\CompanyUsersRestApi\Processor\RestResponseBuilder\CompanyUserRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

abstract class AbstractCompanyUserResourceRelationshipExpander implements CompanyUserResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\CompanyUsersRestApi\Processor\RestResponseBuilder\CompanyUserRestResponseBuilderInterface
     */
    protected $companyUserResponseBuilder;

    /**
     * @var \Spryker\Glue\CompanyUsersRestApi\Processor\Mapper\CompanyUserMapperInterface
     */
    protected $companyUserMapper;

    /**
     * @param \Spryker\Glue\CompanyUsersRestApi\Processor\RestResponseBuilder\CompanyUserRestResponseBuilderInterface $companyUserRestResponseBuilder
     * @param \Spryker\Glue\CompanyUsersRestApi\Processor\Mapper\CompanyUserMapperInterface $companyUserMapper
     */
    public function __construct(
        CompanyUserRestResponseBuilderInterface $companyUserRestResponseBuilder,
        CompanyUserMapperInterface $companyUserMapper
    ) {
        $this->companyUserResponseBuilder = $companyUserRestResponseBuilder;
        $this->companyUserMapper = $companyUserMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        foreach ($resources as $resource) {
            $companyUserTransfer = $this->findCompanyUserTransferInPayload($resource);
            if (!$companyUserTransfer) {
                continue;
            }

            $companyUserRestResource = $this->createCompanyUserRestResource(
                $companyUserTransfer
            );

            $resource->addRelationship($companyUserRestResource);
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    abstract protected function findCompanyUserTransferInPayload(RestResourceInterface $resource): ?CompanyUserTransfer;

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createCompanyUserRestResource(
        CompanyUserTransfer $companyUserTransfer
    ): RestResourceInterface {
        $restCompanyUserAttributesTransfer = $this->companyUserMapper
            ->mapCompanyUserTransferToRestCompanyUserAttributesTransfer(
                $companyUserTransfer,
                new RestCompanyUserAttributesTransfer()
            );

        return $this->companyUserResponseBuilder->createCompanyUsersRestResource(
            $companyUserTransfer->getUuid(),
            $restCompanyUserAttributesTransfer,
            $companyUserTransfer
        );
    }
}
