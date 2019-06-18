<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SharedCartsRestApi\Processor\SharedCart;

use Generated\Shared\Transfer\ShareCartRequestTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use Spryker\Client\SharedCartsRestApi\SharedCartsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SharedCartsRestApi\Processor\RestResponseBuilder\SharedCartRestResponseBuilderInterface;

class SharedCartDeleter implements SharedCartDeleterInterface
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
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function delete(RestRequestInterface $restRequest): RestResponseInterface
    {
        $sharedCartUuid = $restRequest->getResource()->getId();
        if (!$sharedCartUuid) {
            return $this->sharedCartRestResponseBuilder->createSharedCartIdMissingErrorResponse();
        }

        $shareCartRequestTransfer = $this->createShareCartRequestTransfer(
            $sharedCartUuid,
            $restRequest->getRestUser()->getNaturalIdentifier()
        );

        $shareCartResponseTransfer = $this->sharedCartsRestApiClient->delete($shareCartRequestTransfer);
        if (!$shareCartResponseTransfer->getIsSuccessful()) {
            return $this->sharedCartRestResponseBuilder->createErrorResponseFromErrorIdentifier(
                $shareCartResponseTransfer->getErrorIdentifier()
            );
        }

        return $this->sharedCartRestResponseBuilder->createSharedCartRestResponse();
    }

    /**
     * @param string $shareCartUuid
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\ShareCartRequestTransfer
     */
    protected function createShareCartRequestTransfer(string $shareCartUuid, string $customerReference): ShareCartRequestTransfer
    {
        $shareDetailTransfer = (new ShareDetailTransfer())
            ->setUuid($shareCartUuid);

        return (new ShareCartRequestTransfer())
            ->addShareDetail($shareDetailTransfer)
            ->setCustomerReference($customerReference);
    }
}
