<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SharedCartsRestApi\Processor\SharedCart;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Generated\Shared\Transfer\RestSharedCartsAttributesTransfer;
use Generated\Shared\Transfer\ShareCartRequestTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use Spryker\Client\SharedCartsRestApi\SharedCartsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SharedCartsRestApi\Processor\RestResponseBuilder\SharedCartRestResponseBuilderInterface;
use Spryker\Glue\SharedCartsRestApi\SharedCartsRestApiConfig;
use Spryker\Glue\SharedCartsRestApiExtension\Dependency\Plugin\CompanyUserProviderPluginInterface;

class SharedCartCreator implements SharedCartCreatorInterface
{
    /**
     * @var \Spryker\Client\SharedCartsRestApi\SharedCartsRestApiClientInterface
     */
    protected $sharedCartsRestApiClient;

    /**
     * @var \Spryker\Glue\SharedCartsRestApi\Processor\RestResponseBuilder\SharedCartRestResponseBuilderInterface
     */
    protected $sharedCartRestResponseBuilder;

    /**
     * @var \Spryker\Glue\SharedCartsRestApiExtension\Dependency\Plugin\CompanyUserProviderPluginInterface
     */
    protected $companyUserProviderPlugin;

    /**
     * @param \Spryker\Client\SharedCartsRestApi\SharedCartsRestApiClientInterface $sharedCartsRestApiClient
     * @param \Spryker\Glue\SharedCartsRestApi\Processor\RestResponseBuilder\SharedCartRestResponseBuilderInterface $sharedCartRestResponseBuilder
     * @param \Spryker\Glue\SharedCartsRestApiExtension\Dependency\Plugin\CompanyUserProviderPluginInterface $companyUserProviderPlugin
     */
    public function __construct(
        SharedCartsRestApiClientInterface $sharedCartsRestApiClient,
        SharedCartRestResponseBuilderInterface $sharedCartRestResponseBuilder,
        CompanyUserProviderPluginInterface $companyUserProviderPlugin
    ) {
        $this->sharedCartsRestApiClient = $sharedCartsRestApiClient;
        $this->sharedCartRestResponseBuilder = $sharedCartRestResponseBuilder;
        $this->companyUserProviderPlugin = $companyUserProviderPlugin;
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
            return $this->sharedCartRestResponseBuilder->createCartIdMissingErrorResponse();
        }

        $companyUserTransfer = $this->provideCompanyUser($restSharedCartsAttributesTransfer);
        if (!$companyUserTransfer->getIdCompanyUser()) {
            return $this->sharedCartRestResponseBuilder->createCompanyUserNotFoundErrorResponse();
        }

        if (!$this->canManageQuoteSharing($restRequest, $companyUserTransfer)) {
            return $this->sharedCartRestResponseBuilder->createSharingForbiddenErrorResponse();
        }

        $shareCartRequestTransfer = $this->createShareCartRequestTransfer(
            $cartsResource->getId(),
            $restRequest->getRestUser()->getNaturalIdentifier(),
            $companyUserTransfer->getIdCompanyUser(),
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
     * @param \Generated\Shared\Transfer\RestSharedCartsAttributesTransfer $restSharedCartsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function provideCompanyUser(RestSharedCartsAttributesTransfer $restSharedCartsAttributesTransfer): CompanyUserTransfer
    {
        $companyUserTransfer = (new CompanyUserTransfer())
            ->setUuid($restSharedCartsAttributesTransfer->getIdCompanyUser());

        return $this->companyUserProviderPlugin->provideCompanyUser($companyUserTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return bool
     */
    protected function canManageQuoteSharing(RestRequestInterface $restRequest, CompanyUserTransfer $companyUserTransfer): bool
    {
        return $companyUserTransfer->getFkCompany() === $restRequest->getRestUser()->getIdCompany();
    }

    /**
     * @param string $quoteUuid
     * @param string $customerReference
     * @param int $idCompanyUser
     * @param int $idPermissionGroup
     *
     * @return \Generated\Shared\Transfer\ShareCartRequestTransfer
     */
    protected function createShareCartRequestTransfer(
        string $quoteUuid,
        string $customerReference,
        int $idCompanyUser,
        int $idPermissionGroup
    ): ShareCartRequestTransfer {
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
