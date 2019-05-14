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
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Mapper\SharedCartMapperInterface;
use Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Reader\SharedCartReaderInterface;
use Spryker\Glue\SharedCartsRestApi\SharedCartsRestApiConfig;

class SharedCartExpander implements SharedCartExpanderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Mapper\SharedCartMapperInterface
     */
    protected $sharedCartMapper;

    /**
     * @var \Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Reader\SharedCartReaderInterface
     */
    protected $sharedCartReader;

    /**
     * @param \Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Reader\SharedCartReaderInterface $sharedCartReader
     * @param \Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Mapper\SharedCartMapperInterface $sharedCartMapper
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        SharedCartReaderInterface $sharedCartReader,
        SharedCartMapperInterface $sharedCartMapper,
        RestResourceBuilderInterface $restResourceBuilder
    ) {
        $this->sharedCartReader = $sharedCartReader;
        $this->sharedCartMapper = $sharedCartMapper;
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationshipsByCartId(array $resources, RestRequestInterface $restRequest): void
    {
        foreach ($resources as $resource) {
            $quoteTransfer = (new QuoteTransfer())->setUuid($resource->getId());

            $shareDetailCollectionTransfer = $this->sharedCartReader->getSharedCartsByCartUuid($quoteTransfer);
            $this->addSharedCartRelationship($resource, $shareDetailCollectionTransfer);
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     * @param \Generated\Shared\Transfer\ShareDetailCollectionTransfer $shareDetailCollectionTransfer
     *
     * @return void
     */
    protected function addSharedCartRelationship(
        RestResourceInterface $resource,
        ShareDetailCollectionTransfer $shareDetailCollectionTransfer
    ): void {
        foreach ($shareDetailCollectionTransfer->getShareDetails() as $shareDetailTransfer) {
            if (!$shareDetailTransfer->getUuid()) {
                continue;
            }

            $cartSharedCartRestResource = $this->cartSharedCartRestResource($shareDetailTransfer);

            $resource->addRelationship($cartSharedCartRestResource);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ShareDetailTransfer $shareDetailTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function cartSharedCartRestResource(ShareDetailTransfer $shareDetailTransfer): RestResourceInterface
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
