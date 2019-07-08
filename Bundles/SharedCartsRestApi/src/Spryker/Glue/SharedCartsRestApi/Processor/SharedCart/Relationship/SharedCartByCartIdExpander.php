<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Relationship;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestSharedCartsAttributesTransfer;
use Generated\Shared\Transfer\ShareDetailCollectionTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use Spryker\Client\SharedCartsRestApi\SharedCartsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Mapper\SharedCartMapperInterface;
use Spryker\Glue\SharedCartsRestApi\SharedCartsRestApiConfig;

class SharedCartByCartIdExpander implements SharedCartByCartIdExpanderInterface
{
    /**
     * @var \Spryker\Client\SharedCartsRestApi\SharedCartsRestApiClientInterface
     */
    protected $sharedCartsRestApiClient;

    /**
     * @var \Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Mapper\SharedCartMapperInterface
     */
    protected $sharedCartMapper;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Client\SharedCartsRestApi\SharedCartsRestApiClientInterface $sharedCartsRestApiClient
     * @param \Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Mapper\SharedCartMapperInterface $sharedCartMapper
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        SharedCartsRestApiClientInterface $sharedCartsRestApiClient,
        SharedCartMapperInterface $sharedCartMapper,
        RestResourceBuilderInterface $restResourceBuilder
    ) {
        $this->sharedCartsRestApiClient = $sharedCartsRestApiClient;
        $this->sharedCartMapper = $sharedCartMapper;
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        foreach ($resources as $resource) {
            $quoteTransfer = (new QuoteTransfer())->setUuid($resource->getId());

            $shareDetailCollectionTransfer = $this->sharedCartsRestApiClient
                ->getSharedCartsByCartUuid($quoteTransfer);

            $this->addSharedCartRelationships($resource, $shareDetailCollectionTransfer);
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     * @param \Generated\Shared\Transfer\ShareDetailCollectionTransfer $shareDetailCollectionTransfer
     *
     * @return void
     */
    protected function addSharedCartRelationships(
        RestResourceInterface $resource,
        ShareDetailCollectionTransfer $shareDetailCollectionTransfer
    ): void {
        foreach ($shareDetailCollectionTransfer->getShareDetails() as $shareDetailTransfer) {
            if (!$shareDetailTransfer->getUuid()) {
                continue;
            }

            $cartSharedCartRestResource = $this->createSharedCartRestResource($shareDetailTransfer);

            $resource->addRelationship($cartSharedCartRestResource);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ShareDetailTransfer $shareDetailTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createSharedCartRestResource(ShareDetailTransfer $shareDetailTransfer): RestResourceInterface
    {
        $restSharedCartsAttributesTransfer = $this->sharedCartMapper->mapShareDetailTransferToRestSharedCartsAttributesTransfer(
            $shareDetailTransfer,
            new RestSharedCartsAttributesTransfer()
        );

        $sharedCartRestResource = $this->restResourceBuilder->createRestResource(
            SharedCartsRestApiConfig::RESOURCE_SHARED_CARTS,
            $shareDetailTransfer->getUuid(),
            $restSharedCartsAttributesTransfer
        );

        $sharedCartRestResource->setPayload($shareDetailTransfer);

        return $sharedCartRestResource;
    }
}
