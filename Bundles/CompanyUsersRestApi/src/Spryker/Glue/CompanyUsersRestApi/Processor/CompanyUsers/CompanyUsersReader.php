<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUsersRestApi\Processor\CompanyUsers;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\CompanyUsersRestApi\CompanyUsersRestApiConfig;
use Spryker\Glue\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToCompanyUserClientInterface;
use Spryker\Glue\CompanyUsersRestApi\Processor\Mapper\CompanyUsersResourceMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CompanyUsersReader implements CompanyUsersReaderInterface
{
    /**
     * @var \Spryker\Glue\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToCompanyUserClientInterface
     */
    protected $companyUserClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CompanyUsersRestApi\Processor\Mapper\CompanyUsersResourceMapperInterface
     */
    protected $companyUsersResourceMapper;

    /**
     * @param \Spryker\Glue\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToCompanyUserClientInterface $companyUserClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CompanyUsersRestApi\Processor\Mapper\CompanyUsersResourceMapperInterface $companyUsersResourceMapper
     */
    public function __construct(
        CompanyUsersRestApiToCompanyUserClientInterface $companyUserClient,
        RestResourceBuilderInterface $restResourceBuilder,
        CompanyUsersResourceMapperInterface $companyUsersResourceMapper
    ) {
        $this->companyUserClient = $companyUserClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->companyUsersResourceMapper = $companyUsersResourceMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCompanyUsersByCustomerReference(RestRequestInterface $restRequest): RestResponseInterface
    {
        $customerTransfer = (new CustomerTransfer())->setCustomerReference($restRequest->getUser()->getNaturalIdentifier());
        $companyUserCollectionTransfer = $this->companyUserClient->getActiveCompanyUsersByCustomerReference($customerTransfer);

        $restResponse = $this->getCompanyUsersResponse(
            $this->restResourceBuilder->createRestResponse(),
            $companyUserCollectionTransfer
        );

        $restResponse->addLink(RestLinkInterface::LINK_SELF, $this->createSelfLink());

        return $restResponse;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     * @param \Generated\Shared\Transfer\CompanyUserCollectionTransfer $companyUserCollectionTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getCompanyUsersResponse(
        RestResponseInterface $restResponse,
        CompanyUserCollectionTransfer $companyUserCollectionTransfer
    ): RestResponseInterface {
        foreach ($companyUserCollectionTransfer->getCompanyUsers() as $companyUserTransfer) {
            $restCompanyUserAttributesTransfer = $this->companyUsersResourceMapper
                ->mapCompanyUserTransferToRestCompanyUserAttributesTransfer(
                    $companyUserTransfer
                );

            $restResource = $this->restResourceBuilder->createRestResource(
                CompanyUsersRestApiConfig::RESOURCE_COMPANY_USERS,
                (string)$companyUserTransfer->getIdCompanyUser(),
                $restCompanyUserAttributesTransfer
            );

            $restResponse->addResource($restResource)
                ->addLink(RestLinkInterface::LINK_SELF, $this->createSelfLink());
        }

        return $restResponse;
    }

    /**
     * @return string
     */
    protected function createSelfLink(): string
    {
        return sprintf(
            CompanyUsersRestApiConfig::FORMAT_SELF_LINK_COMPANY_USERS_RESOURCE,
            CompanyUsersRestApiConfig::RESOURCE_COMPANY_USERS
        );
    }
}
