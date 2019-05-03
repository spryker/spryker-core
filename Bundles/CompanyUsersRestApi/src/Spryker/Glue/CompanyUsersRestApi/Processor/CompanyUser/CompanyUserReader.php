<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUsersRestApi\Processor\CompanyUser;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\RestCompanyUserAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Client\CompanyUsersRestApi\CompanyUsersRestApiClientInterface;
use Spryker\Glue\CompanyUsersRestApi\CompanyUsersRestApiConfig;
use Spryker\Glue\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToCompanyUserClientInterface;
use Spryker\Glue\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToCompanyUserStorageClientInterface;
use Spryker\Glue\CompanyUsersRestApi\Processor\RestResponseBuilder\CompanyUserRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class CompanyUserReader implements CompanyUserReaderInterface
{
    protected const MAPPING_TYPE_UUID = 'uuid';

    /**
     * @uses \Spryker\Glue\CompanyBusinessUnitsRestApi\CompanyBusinessUnitsRestApiConfig::RESOURCE_COMPANY_BUSINESS_UNITS
     */
    protected const RESOURCE_COMPANY_BUSINESS_UNITS = 'company-business-units';

    /**
     * @var \Spryker\Glue\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToCompanyUserClientInterface
     */
    protected $companyUserClient;

    /**
     * @var \Spryker\Client\CompanyUsersRestApi\CompanyUsersRestApiClientInterface
     */
    protected $companyUsersRestApiClient;

    /**
     * @var \Spryker\Glue\CompanyUsersRestApi\Processor\RestResponseBuilder\CompanyUserRestResponseBuilderInterface
     */
    protected $companyUserRestResponse;

    /**
     * @var \Spryker\Glue\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToCompanyUserStorageClientInterface
     */
    protected $companyUserStorageClient;

    /**
     * @param \Spryker\Glue\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToCompanyUserClientInterface $companyUserClient
     * @param \Spryker\Client\CompanyUsersRestApi\CompanyUsersRestApiClientInterface $companyUsersRestApiClient
     * @param \Spryker\Glue\CompanyUsersRestApi\Processor\RestResponseBuilder\CompanyUserRestResponseBuilderInterface $companyUserRestResponse
     * @param \Spryker\Glue\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToCompanyUserStorageClientInterface $companyUserStorageClient
     */
    public function __construct(
        CompanyUsersRestApiToCompanyUserClientInterface $companyUserClient,
        CompanyUsersRestApiClientInterface $companyUsersRestApiClient,
        CompanyUserRestResponseBuilderInterface $companyUserRestResponse,
        CompanyUsersRestApiToCompanyUserStorageClientInterface $companyUserStorageClient
    ) {
        $this->companyUserClient = $companyUserClient;
        $this->companyUsersRestApiClient = $companyUsersRestApiClient;
        $this->companyUserRestResponse = $companyUserRestResponse;
        $this->companyUserStorageClient = $companyUserStorageClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCompanyUsersByCustomerReference(RestRequestInterface $restRequest): RestResponseInterface
    {
        $customerTransfer = (new CustomerTransfer())
            ->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier());
        $companyUserCollectionTransfer = $this->companyUserClient
            ->getActiveCompanyUsersByCustomerReference($customerTransfer);

        return $this->companyUserRestResponse->buildCompanyUserCollectionResponse($companyUserCollectionTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCompanyUserByResourceId(RestRequestInterface $restRequest): RestResponseInterface
    {
        $idResource = $restRequest->getResource()->getId();

        if ($idResource === CompanyUsersRestApiConfig::CURRENT_USER_COLLECTION_IDENTIFIER) {
            return $this->getCompanyUsersByCustomerReference($restRequest);
        }

        return $this->getCompanyUser($idResource, $restRequest);
    }

    /**
     * @param string $companyUserUuid
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getCompanyUser(string $companyUserUuid, RestRequestInterface $restRequest): RestResponseInterface
    {
        $companyUserStorageTransfer = $this->companyUserStorageClient
            ->findCompanyUserByMapping(static::MAPPING_TYPE_UUID, $companyUserUuid);
        if (!$companyUserStorageTransfer) {
            return $this->companyUserRestResponse->buildNotFoundErrorResponse();
        }

        $companyUserTransfer = (new CompanyUserTransfer())
            ->setUuid($companyUserUuid)
            ->setIdCompanyUser($companyUserStorageTransfer->getIdCompanyUser());
        $companyUserTransfer = $this->companyUserClient->getCompanyUserById($companyUserTransfer);

        if ($companyUserTransfer->getCompany()->getIdCompany() !== $restRequest->getRestUser()->getIdCompany()) {
            return $this->companyUserRestResponse->buildForbiddenErrorResponse();
        }

        return $this->companyUserRestResponse->buildCompanyUserResponse($companyUserTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCompanyUserCollection(RestRequestInterface $restRequest): RestResponseInterface
    {
        $idCompany = $restRequest->getRestUser()->getIdCompany();
        if (!$idCompany) {
            return $this->buildForbiddenErrorResponse();
        }

        $companyUserCriteriaFilterTransfer = (new CompanyUserCriteriaFilterTransfer())
            ->setIdCompany($idCompany)
            ->setFilter($this->createFilterTransfer($restRequest));

        if ($restRequest->hasFilters(CompanyUsersRestApiConfig::RESOURCE_COMPANY_USERS)) {
            $this->addFilterByCompanyUsers($restRequest, $companyUserCriteriaFilterTransfer);
        }

        if ($restRequest->hasFilters(static::RESOURCE_COMPANY_BUSINESS_UNITS)) {
            $this->addFilterByCompanyBusinessUnits($restRequest, $companyUserCriteriaFilterTransfer);
        }

        $companyUserCollectionTransfer = $this->companyUsersRestApiClient->getCompanyUserCollection(
            $companyUserCriteriaFilterTransfer->setFilter($this->createFilterTransfer($restRequest))
        );

        return $this->companyUserRestResponse->buildCompanyUserCollectionResponse(
            $companyUserCollectionTransfer,
            $companyUserCollectionTransfer->getTotal(),
            $companyUserCollectionTransfer->getFilter()->getLimit()
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
     *
     * @return void
     */
    protected function addFilterByCompanyUsers(RestRequestInterface $restRequest, CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer): void
    {
        $filterCompanyUsers = $restRequest->getFiltersByResource(CompanyUsersRestApiConfig::RESOURCE_COMPANY_USERS);
        foreach ($filterCompanyUsers as $filterCompanyUser) {
            $companyUserUuid = $filterCompanyUser->getValue();
            $companyUserStorageTransfer = $this->companyUserStorageClient
                ->findCompanyUserByMapping(static::MAPPING_TYPE_UUID, $companyUserUuid);
            if ($companyUserStorageTransfer) {
                $companyUserCriteriaFilterTransfer
                    ->addCompanyUserIds($companyUserStorageTransfer->getIdCompanyUser());
            }
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
     *
     * @return void
     */
    protected function addFilterByCompanyBusinessUnits(RestRequestInterface $restRequest, CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer): void
    {
        $filterCompanyBusinessUnits = $restRequest->getFiltersByResource(static::RESOURCE_COMPANY_BUSINESS_UNITS);
        foreach ($filterCompanyBusinessUnits as $companyBusinessUnit) {
            $companyBusinessUnitUuid = $companyBusinessUnit->getValue();
            $companyUserStorageTransfer = $this->companyUserStorageClient
                ->findCompanyUserByMapping(static::MAPPING_TYPE_UUID, $companyBusinessUnitUuid);
            if ($companyUserStorageTransfer) {
                $companyUserCriteriaFilterTransfer
                    ->addCompanyBusinessUnitUuids($companyUserStorageTransfer->getUuid());
            }
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\FilterTransfer
     */
    protected function createFilterTransfer(RestRequestInterface $restRequest): FilterTransfer
    {
        return (new FilterTransfer())
            ->setOffset($restRequest->getPage() ? $restRequest->getPage()->getOffset() : 0)
            ->setLimit($restRequest->getPage() ? $restRequest->getPage()->getLimit() : 0);
    }
}
