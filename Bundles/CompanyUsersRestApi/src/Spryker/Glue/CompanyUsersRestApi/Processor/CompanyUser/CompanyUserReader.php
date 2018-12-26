<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUsersRestApi\Processor\CompanyUser;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestCompanyUserAttributesTransfer;
use Spryker\Glue\CompanyUsersRestApi\CompanyUsersRestApiConfig;
use Spryker\Glue\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToCompanyUserClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CompanyUserReader implements CompanyUserReaderInterface
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
     * @var \Spryker\Glue\CompanyUsersRestApiExtension\Dependency\Plugin\CompanyUsersResourceExpanderPluginInterface[]
     */
    protected $companyUsersResourceExpanderPlugins;

    /**
     * @param \Spryker\Glue\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToCompanyUserClientInterface $companyUserClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CompanyUsersRestApiExtension\Dependency\Plugin\CompanyUsersResourceExpanderPluginInterface[] $companyUsersResourceExpanderPlugins
     */
    public function __construct(
        CompanyUsersRestApiToCompanyUserClientInterface $companyUserClient,
        RestResourceBuilderInterface $restResourceBuilder,
        array $companyUsersResourceExpanderPlugins
    ) {
        $this->companyUserClient = $companyUserClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->companyUsersResourceExpanderPlugins = $companyUsersResourceExpanderPlugins;
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

        return $this->getCompanyUsersResponse($companyUserCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserCollectionTransfer $companyUserCollectionTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getCompanyUsersResponse(CompanyUserCollectionTransfer $companyUserCollectionTransfer): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        foreach ($companyUserCollectionTransfer->getCompanyUsers() as $companyUserTransfer) {
            $restCompanyUserAttributesTransfer = (new RestCompanyUserAttributesTransfer())
                ->fromArray($companyUserTransfer->toArray(), true);

            $this->expandRestCompanyUserAttributesTransferWithCompanyUserData(
                $restCompanyUserAttributesTransfer,
                $companyUserTransfer
            );

            $restResource = $this->restResourceBuilder->createRestResource(
                CompanyUsersRestApiConfig::RESOURCE_COMPANY_USERS,
                (string)$companyUserTransfer->getIdCompanyUser(),
                $restCompanyUserAttributesTransfer
            );

            $restResponse->addResource($restResource);
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCompanyUserAttributesTransfer $restCompanyUserAttributesTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\RestCompanyUserAttributesTransfer
     */
    protected function expandRestCompanyUserAttributesTransferWithCompanyUserData(
        RestCompanyUserAttributesTransfer $restCompanyUserAttributesTransfer,
        CompanyUserTransfer $companyUserTransfer
    ): RestCompanyUserAttributesTransfer {
        foreach ($this->companyUsersResourceExpanderPlugins as $companyUserResourceExpanderPlugin) {
            $restCompanyUserAttributesTransfer = $companyUserResourceExpanderPlugin->expand(
                $companyUserTransfer,
                $restCompanyUserAttributesTransfer
            );
        }

        return $restCompanyUserAttributesTransfer;
    }
}
