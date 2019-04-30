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
use Spryker\Glue\CompanyUsersRestApi\Processor\Mapper\CompanyUserMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class CompanyUserReader implements CompanyUserReaderInterface
{
    protected const MAPPING_TYPE_UUID = 'uuid';

    /**
     * @var \Spryker\Glue\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToCompanyUserClientInterface
     */
    protected $companyUserClient;

    /**
     * @var \Spryker\Client\CompanyUsersRestApi\CompanyUsersRestApiClientInterface
     */
    protected $companyUsersRestApiClient;

    /**
     * @var \Spryker\Glue\CompanyUsersRestApi\Processor\Mapper\CompanyUserMapperInterface
     */
    protected $companyUserMapper;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToCompanyUserStorageClientInterface
     */
    protected $companyUserStorageClient;

    /**
     * @param \Spryker\Glue\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToCompanyUserClientInterface $companyUserClient
     * @param \Spryker\Client\CompanyUsersRestApi\CompanyUsersRestApiClientInterface $companyUsersRestApiClient
     * @param \Spryker\Glue\CompanyUsersRestApi\Processor\Mapper\CompanyUserMapperInterface $companyUserMapper
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToCompanyUserStorageClientInterface $companyUserStorageClient
     */
    public function __construct(
        CompanyUsersRestApiToCompanyUserClientInterface $companyUserClient,
        CompanyUsersRestApiClientInterface $companyUsersRestApiClient,
        CompanyUserMapperInterface $companyUserMapper,
        RestResourceBuilderInterface $restResourceBuilder,
        CompanyUsersRestApiToCompanyUserStorageClientInterface $companyUserStorageClient
    ) {
        $this->companyUserClient = $companyUserClient;
        $this->companyUsersRestApiClient = $companyUsersRestApiClient;
        $this->companyUserMapper = $companyUserMapper;
        $this->restResourceBuilder = $restResourceBuilder;
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

        return $this->buildCompanyUserCollectionResponse($companyUserCollectionTransfer);
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
            return $this->buildNotFoundErrorResponse();
        }

        $companyUserTransfer = (new CompanyUserTransfer())
            ->setUuid($companyUserUuid)
            ->setIdCompanyUser($companyUserStorageTransfer->getIdCompanyUser());
        $companyUserTransfer = $this->companyUserClient->getCompanyUserById($companyUserTransfer);

        if ($companyUserTransfer->getCompany()->getIdCompany() !== $restRequest->getRestUser()->getIdCompany()) {
            return $this->buildForbiddenErrorResponse();
        }

        $restCompanyUserAttributesTransfer = $this->companyUserMapper
            ->mapCompanyUserTransferToRestCompanyUserAttributesTransfer(
                $companyUserTransfer,
                new RestCompanyUserAttributesTransfer()
            );

        $restResource = $this->restResourceBuilder->createRestResource(
            CompanyUsersRestApiConfig::RESOURCE_COMPANY_USERS,
            $companyUserTransfer->getUuid(),
            $restCompanyUserAttributesTransfer
        );

        $restResource->setPayload($companyUserTransfer);

        return $this->restResourceBuilder->createRestResponse()->addResource($restResource);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCompanyUserCollection(RestRequestInterface $restRequest): RestResponseInterface
    {
        $companyUserCriteriaFilterTransfer = (new CompanyUserCriteriaFilterTransfer())
            ->setIdCompany($restRequest->getRestUser()->getIdCompany());

        if ($restRequest->hasFilters(CompanyUsersRestApiConfig::RESOURCE_COMPANY_USERS)) {
            $filterCompanyUsers = $restRequest->getFiltersByResource(CompanyUsersRestApiConfig::RESOURCE_COMPANY_USERS);
            foreach ($filterCompanyUsers as $filterCompanyUser) {
                $companyUserUuid = $filterCompanyUser->getValue();
                $companyUserStorageTransfer = $this->companyUserStorageClient
                    ->findCompanyUserByMapping(static::MAPPING_TYPE_UUID, $companyUserUuid);
                $companyUserTransfer = (new CompanyUserTransfer())->setIdCompanyUser($companyUserStorageTransfer->getIdCompanyUser());
                $companyUserCriteriaFilterTransfer->addCompanyUserIds($companyUserTransfer->getIdCompanyUser());
            }

            $companyUserCollectionTransfer = $this->companyUsersRestApiClient->getCompanyUserCollection($companyUserCriteriaFilterTransfer);

            return $this->buildCompanyUserCollectionResponse($companyUserCollectionTransfer);
        }

        $companyUserCollectionTransfer = $this->companyUsersRestApiClient
            ->getCompanyUserCollection($companyUserCriteriaFilterTransfer)
            ->setFilter($this->createFilterTransfer($restRequest));

        return $this->buildCompanyUserCollectionResponse(
            $companyUserCollectionTransfer,
            $companyUserCollectionTransfer->getTotal(),
            $companyUserCollectionTransfer->getFilter()->getLimit()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserCollectionTransfer $companyUserCollectionTransfer
     * @param int $totalItems
     * @param int $limit
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function buildCompanyUserCollectionResponse(
        CompanyUserCollectionTransfer $companyUserCollectionTransfer,
        int $totalItems = 0,
        int $limit = 0
    ): RestResponseInterface {
        $restResponse = $this->restResourceBuilder->createRestResponse(
            $totalItems,
            $limit
        );

        foreach ($companyUserCollectionTransfer->getCompanyUsers() as $companyUserTransfer) {
            $restCompanyUserAttributesTransfer = $this->companyUserMapper
                ->mapCompanyUserTransferToRestCompanyUserAttributesTransfer(
                    $companyUserTransfer,
                    new RestCompanyUserAttributesTransfer()
                );

            $restResource = $this->restResourceBuilder->createRestResource(
                CompanyUsersRestApiConfig::RESOURCE_COMPANY_USERS,
                $companyUserTransfer->getUuid(),
                $restCompanyUserAttributesTransfer
            );

            $restResource->setPayload($companyUserTransfer);

            $restResponse->addResource($restResource);
        }

        return $restResponse;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function buildNotImplementedErrorResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setStatus(Response::HTTP_NOT_IMPLEMENTED)
            ->setCode(CompanyUsersRestApiConfig::RESPONSE_CODE_RESOURCE_NOT_IMPLEMENTED)
            ->setDetail(CompanyUsersRestApiConfig::RESPONSE_DETAIL_RESOURCE_NOT_IMPLEMENTED);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorMessageTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function buildNotFoundErrorResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setCode(CompanyUsersRestApiConfig::RESPONSE_CODE_COMPANY_USER_NOT_FOUND)
            ->setDetail(CompanyUsersRestApiConfig::RESPONSE_DETAIL_COMPANY_USER_NOT_FOUND);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorMessageTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function buildForbiddenErrorResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setStatus(Response::HTTP_FORBIDDEN)
            ->setCode(CompanyUsersRestApiConfig::RESPONSE_CODE_ACCESS_FORBIDDEN)
            ->setDetail(CompanyUsersRestApiConfig::RESPONSE_DETAIL_ACCESS_FORBIDDEN);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorMessageTransfer);
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
