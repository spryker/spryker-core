<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SharedCartsRestApi\Processor\SharedCart;

use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Generated\Shared\Transfer\RestSharedCartsAttributesTransfer;
use Generated\Shared\Transfer\ShareCartRequestTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use Spryker\Client\SharedCartsRestApi\SharedCartsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SharedCartsRestApi\Processor\RestResponseBuilder\SharedCartRestResponseBuilderInterface;

class SharedCartUpdater implements SharedCartUpdaterInterface
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
     * @param \Spryker\Client\SharedCartsRestApi\SharedCartsRestApiClientInterface $sharedCartsRestApiClient
     * @param \Spryker\Glue\SharedCartsRestApi\Processor\RestResponseBuilder\SharedCartRestResponseBuilderInterface $sharedCartRestResponseBuilder
     */
    public function __construct(
        SharedCartsRestApiClientInterface $sharedCartsRestApiClient,
        SharedCartRestResponseBuilderInterface $sharedCartRestResponseBuilder
    ) {
        $this->sharedCartsRestApiClient = $sharedCartsRestApiClient;
        $this->sharedCartRestResponseBuilder = $sharedCartRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestSharedCartsAttributesTransfer $restSharedCartsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function update(RestRequestInterface $restRequest, RestSharedCartsAttributesTransfer $restSharedCartsAttributesTransfer): RestResponseInterface
    {
        $sharedCartUuid = $restRequest->getResource()->getId();
        if (!$sharedCartUuid) {
            return $this->sharedCartRestResponseBuilder->createSharedCartIdMissingErrorResponse();
        }

        $shareCartRequestTransfer = $this->createShareCartRequestTransfer(
            $sharedCartUuid,
            $restRequest->getRestUser()->getNaturalIdentifier(),
            $restSharedCartsAttributesTransfer->getIdCartPermissionGroup()
        );

        $shareCartResponseTransfer = $this->sharedCartsRestApiClient->update($shareCartRequestTransfer);
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
     * @param string $sharedCartUuid
     * @param string $customerReference
     * @param int $idPermissionGroup
     *
     * @return \Generated\Shared\Transfer\ShareCartRequestTransfer
     */
    protected function createShareCartRequestTransfer(
        string $sharedCartUuid,
        string $customerReference,
        int $idPermissionGroup
    ): ShareCartRequestTransfer {
        $quotePermissionGroupTransfer = (new QuotePermissionGroupTransfer())
            ->setIdQuotePermissionGroup($idPermissionGroup);
        $shareDetailTransfer = (new ShareDetailTransfer())
            ->setQuotePermissionGroup($quotePermissionGroupTransfer)
            ->setUuid($sharedCartUuid);

        return (new ShareCartRequestTransfer())
            ->addShareDetail($shareDetailTransfer)
            ->setCustomerReference($customerReference);
    }
}
