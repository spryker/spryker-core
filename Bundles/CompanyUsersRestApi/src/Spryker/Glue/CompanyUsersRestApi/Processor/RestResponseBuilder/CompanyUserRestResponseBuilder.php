<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUsersRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\RestCompanyUserAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CompanyUsersRestApi\CompanyUsersRestApiConfig;
use Spryker\Glue\CompanyUsersRestApi\Processor\Mapper\CompanyUserMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class CompanyUserRestResponseBuilder implements CompanyUserRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CompanyUsersRestApi\Processor\Mapper\CompanyUserMapperInterface
     */
    protected $companyUserMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CompanyUsersRestApi\Processor\Mapper\CompanyUserMapperInterface $companyUserMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        CompanyUserMapperInterface $companyUserMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->companyUserMapper = $companyUserMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyUserResponse(CompanyUserTransfer $companyUserTransfer): RestResponseInterface
    {
        $companyUserRestResource = $this->createCompanyUserResource($companyUserTransfer);

        return $this->restResourceBuilder
            ->createRestResponse()
            ->addResource($companyUserRestResource);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserCollectionTransfer $companyUserCollectionTransfer
     * @param int $totalItems
     * @param int $limit
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyUserCollectionResponse(
        CompanyUserCollectionTransfer $companyUserCollectionTransfer,
        int $totalItems = 0,
        int $limit = 0
    ): RestResponseInterface {
        $restResponse = $this->restResourceBuilder->createRestResponse(
            $totalItems,
            $limit
        );

        foreach ($companyUserCollectionTransfer->getCompanyUsers() as $companyUserTransfer) {
            $companyUserRestResource = $this->createCompanyUserResource($companyUserTransfer);
            $restResponse->addResource($companyUserRestResource);
        }

        return $restResponse;
    }

    /**
     * @param string $companyUserUuid
     * @param \Generated\Shared\Transfer\RestCompanyUserAttributesTransfer $restCompanyUserAttributesTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createCompanyUsersRestResource(
        string $companyUserUuid,
        RestCompanyUserAttributesTransfer $restCompanyUserAttributesTransfer,
        CompanyUserTransfer $companyUserTransfer
    ): RestResourceInterface {
        $companyUserRestResource = $this->restResourceBuilder->createRestResource(
            CompanyUsersRestApiConfig::RESOURCE_COMPANY_USERS,
            $companyUserUuid,
            $restCompanyUserAttributesTransfer
        );

        $companyUserRestResource->setPayload($companyUserTransfer);

        return $companyUserRestResource;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyUserNotSelectedErrorResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setStatus(Response::HTTP_FORBIDDEN)
            ->setCode(CompanyUsersRestApiConfig::RESPONSE_CODE_COMPANY_USER_NOT_SELECTED)
            ->setDetail(CompanyUsersRestApiConfig::RESPONSE_DETAIL_COMPANY_USER_NOT_SELECTED);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorMessageTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCompanyUserNotFoundErrorResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setCode(CompanyUsersRestApiConfig::RESPONSE_CODE_COMPANY_USER_NOT_FOUND)
            ->setDetail(CompanyUsersRestApiConfig::RESPONSE_DETAIL_COMPANY_USER_NOT_FOUND);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorMessageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createCompanyUserResource(CompanyUserTransfer $companyUserTransfer): RestResourceInterface
    {
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

        return $restResource;
    }
}
