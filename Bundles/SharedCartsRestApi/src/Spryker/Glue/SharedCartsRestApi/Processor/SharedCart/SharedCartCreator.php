<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SharedCartsRestApi\Processor\SharedCart;

use Generated\Shared\Transfer\CompanyUserStorageTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Generated\Shared\Transfer\RestSharedCartsAttributesTransfer;
use Generated\Shared\Transfer\ShareCartRequestTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use Spryker\Client\SharedCartsRestApi\SharedCartsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SharedCartsRestApi\Dependency\Client\SharedCartsRestApiToCompanyUserStorageClientInterface;
use Spryker\Glue\SharedCartsRestApi\Processor\RestResponseBuilder\SharedCartRestResponseBuilderInterface;
use Spryker\Glue\SharedCartsRestApi\SharedCartsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class SharedCartCreator implements SharedCartCreatorInterface
{
    protected const MAPPING_TYPE_UUID = 'uuid';

    /**
     * @var \Spryker\Client\SharedCartsRestApi\SharedCartsRestApiClientInterface
     */
    protected $sharedCartsRestApiClient;

    /**
     * @var \Spryker\Glue\SharedCartsRestApi\Dependency\Client\SharedCartsRestApiToCompanyUserStorageClientInterface
     */
    protected $companyUserStorageClient;

    /**
     * @var \Spryker\Glue\SharedCartsRestApi\Processor\RestResponseBuilder\SharedCartRestResponseBuilderInterface
     */
    protected $sharedCartRestResponseBuilder;

    /**
     * @param \Spryker\Client\SharedCartsRestApi\SharedCartsRestApiClientInterface $sharedCartsRestApiClient
     * @param \Spryker\Glue\SharedCartsRestApi\Dependency\Client\SharedCartsRestApiToCompanyUserStorageClientInterface $companyUserStorageClient
     * @param \Spryker\Glue\SharedCartsRestApi\Processor\RestResponseBuilder\SharedCartRestResponseBuilderInterface $sharedCartRestResponseBuilder
     */
    public function __construct(
        SharedCartsRestApiClientInterface $sharedCartsRestApiClient,
        SharedCartsRestApiToCompanyUserStorageClientInterface $companyUserStorageClient,
        SharedCartRestResponseBuilderInterface $sharedCartRestResponseBuilder
    ) {
        $this->sharedCartsRestApiClient = $sharedCartsRestApiClient;
        $this->companyUserStorageClient = $companyUserStorageClient;
        $this->sharedCartRestResponseBuilder = $sharedCartRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestSharedCartsAttributesTransfer $restSharedCartsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function create(
        RestRequestInterface $restRequest,
        RestSharedCartsAttributesTransfer $restSharedCartsAttributesTransfer
    ): RestResponseInterface {
        $cartsResource = $restRequest->findParentResourceByType(SharedCartsRestApiConfig::RESOURCE_CARTS);
        if (!$cartsResource || !$cartsResource->getId()) {
            return $this->sharedCartRestResponseBuilder->createRestErrorResponse(
                Response::HTTP_BAD_REQUEST,
                SharedCartsRestApiConfig::RESPONSE_CODE_CART_ID_MISSING,
                SharedCartsRestApiConfig::EXCEPTION_MESSAGE_CART_ID_MISSING
            );
        }

        $companyUserStorageTransfer = $this->companyUserStorageClient->findCompanyUserByMapping(
            static::MAPPING_TYPE_UUID,
            $restSharedCartsAttributesTransfer->getIdCompanyUser()
        );
        if (!$companyUserStorageTransfer) {
            return $this->sharedCartRestResponseBuilder->createRestErrorResponse(
                Response::HTTP_NOT_FOUND,
                SharedCartsRestApiConfig::RESPONSE_CODE_COMPANY_USER_NOT_FOUND,
                SharedCartsRestApiConfig::RESPONSE_DETAIL_COMPANY_USER_NOT_FOUND
            );
        }

        if (!$this->isCartCanBeShared($restRequest, $companyUserStorageTransfer)) {
            return $this->sharedCartRestResponseBuilder->createRestErrorResponse(
                Response::HTTP_FORBIDDEN,
                SharedCartsRestApiConfig::RESPONSE_CODE_CAN_ONLY_SHARE_CART_WITH_COMPANY_USERS_FROM_SAME_COMPANY,
                SharedCartsRestApiConfig::RESPONSE_DETAIL_CAN_ONLY_SHARE_CART_WITH_COMPANY_USERS_FROM_SAME_COMPANY
            );
        }

        $shareCartRequestTransfer = $this->createShareCartRequestTransfer(
            $cartsResource->getId(),
            $restRequest->getRestUser()->getNaturalIdentifier(),
            $companyUserStorageTransfer->getIdCompanyUser(),
            $restSharedCartsAttributesTransfer->getIdCartPermissionGroup()
        );

        $shareCartResponseTransfer = $this->sharedCartsRestApiClient->create($shareCartRequestTransfer);
        if (!$shareCartResponseTransfer->getIsSuccessful()) {
            return $this->sharedCartRestResponseBuilder->createErrorResponseFromErrorIdentifier(
                $shareCartResponseTransfer->getErrorIdentifier()
            );
        }

        return $this->sharedCartRestResponseBuilder->createSharedCartRestResponse(
            $shareCartResponseTransfer->getShareDetails()->offsetGet(0)
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\CompanyUserStorageTransfer $companyUserStorageTransfer
     *
     * @return bool
     */
    protected function isCartCanBeShared(RestRequestInterface $restRequest, CompanyUserStorageTransfer $companyUserStorageTransfer): bool
    {
        return $companyUserStorageTransfer->getIdCompany() === $restRequest->getRestUser()->getIdCompany();
    }

    /**
     * @param string $quoteUuid
     * @param string $customerReference
     * @param int $idCompanyUser
     * @param int $idPermissionGroup
     *
     * @return \Generated\Shared\Transfer\ShareCartRequestTransfer
     */
    protected function createShareCartRequestTransfer(string $quoteUuid, string $customerReference, int $idCompanyUser, int $idPermissionGroup): ShareCartRequestTransfer
    {
        $quotePermissionGroupTransfer = (new QuotePermissionGroupTransfer())->setIdQuotePermissionGroup($idPermissionGroup);
        $shareDetailTransfer = (new ShareDetailTransfer())
            ->setQuotePermissionGroup($quotePermissionGroupTransfer)
            ->setIdCompanyUser($idCompanyUser);

        return (new ShareCartRequestTransfer())
            ->addShareDetail($shareDetailTransfer)
            ->setQuoteUuid($quoteUuid)
            ->setCustomerReference($customerReference);
    }
}
