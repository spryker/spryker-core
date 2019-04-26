<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUsersRestApi\Processor\CompanyUser;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\RestCompanyUserAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Client\CompanyUsersRestApi\CompanyUsersRestApiClientInterface;
use Spryker\Glue\CompanyUsersRestApi\CompanyUsersRestApiConfig;
use Spryker\Glue\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToCompanyUserClientInterface;
use Spryker\Glue\CompanyUsersRestApi\Processor\Mapper\CompanyUserMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class CompanyUserReader implements CompanyUserReaderInterface
{
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
     * @param \Spryker\Glue\CompanyUsersRestApi\Dependency\Client\CompanyUsersRestApiToCompanyUserClientInterface $companyUserClient
     * @param \Spryker\Client\CompanyUsersRestApi\CompanyUsersRestApiClientInterface $companyUsersRestApiClient
     * @param \Spryker\Glue\CompanyUsersRestApi\Processor\Mapper\CompanyUserMapperInterface $companyUserMapper
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        CompanyUsersRestApiToCompanyUserClientInterface $companyUserClient,
        CompanyUsersRestApiClientInterface $companyUsersRestApiClient,
        CompanyUserMapperInterface $companyUserMapper,
        RestResourceBuilderInterface $restResourceBuilder
    ) {
        $this->companyUserClient = $companyUserClient;
        $this->companyUsersRestApiClient = $companyUsersRestApiClient;
        $this->companyUserMapper = $companyUserMapper;
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCompanyUsersByCustomerReference(RestRequestInterface $restRequest): RestResponseInterface
    {
        $idCompanyUser = $restRequest->getResource()->getId();

        if ($idCompanyUser !== null) {
            return $this->buildNotImplementedErrorResponse();
        }

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
    public function getCompanyUser(string $companyUserUuid, RestRequestInterface $restRequest): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse();
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCompanyUserCollection(RestRequestInterface $restRequest): RestResponseInterface
    {
        $companyUserCriteriaFilterTransfer = (new CompanyUserCriteriaFilterTransfer())
            ->setIdCompany($restRequest->getRestUser()->getIdCompany())
            ->setFilter($this->createFilterTransfer($restRequest));

        $companyUserCollectionTransfer = $this->companyUsersRestApiClient
            ->getCompanyUserCollection($companyUserCriteriaFilterTransfer);

        return $this->buildCompanyUserCollectionResponse($companyUserCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserCollectionTransfer $companyUserCollectionTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function buildCompanyUserCollectionResponse(
        CompanyUserCollectionTransfer $companyUserCollectionTransfer
    ): RestResponseInterface {
        $restResponse = $this->restResourceBuilder->createRestResponse(
            $companyUserCollectionTransfer->getTotal(),
            $companyUserCollectionTransfer->getFilter()->getLimit()
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
