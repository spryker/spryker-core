<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Relationship;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestSharedCartsAttributesTransfer;
use Generated\Shared\Transfer\ShareDetailCollectionTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SharedCartsRestApi\Processor\Mapper\SharedCartMapperInterface;
use Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\SharedCartReaderInterface;
use Spryker\Glue\SharedCartsRestApi\SharedCartsRestApiConfig;

class SharedCartExpander implements SharedCartExpanderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\SharedCartReaderInterface
     */
    protected $sharedCartReader;

    /**
     * @var \Spryker\Glue\SharedCartsRestApi\Processor\Mapper\SharedCartMapperInterface
     */
    protected $sharedCartsResourceMapper;

    /**
     * @param \Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\SharedCartReaderInterface $sharedCartReader
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\SharedCartsRestApi\Processor\Mapper\SharedCartMapperInterface $sharedCartsResourceMapper
     */
    public function __construct(
        SharedCartReaderInterface $sharedCartReader,
        RestResourceBuilderInterface $restResourceBuilder,
        SharedCartMapperInterface $sharedCartsResourceMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->sharedCartReader = $sharedCartReader;
        $this->sharedCartsResourceMapper = $sharedCartsResourceMapper;
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
    protected function addSharedCartRelationship(RestResourceInterface $resource, ShareDetailCollectionTransfer $shareDetailCollectionTransfer): void
    {
        foreach ($shareDetailCollectionTransfer->getShareDetails() as $shareDetailTransfer) {
            if (!$shareDetailTransfer->getUuid()) {
                continue;
            }
            $restSharedCartsAttributesTransfer = $this->sharedCartsResourceMapper->mapShareDetailTransferToRestSharedCartsAttributeTransfer(
                $shareDetailTransfer,
                new RestSharedCartsAttributesTransfer()
            );

            $resource->addRelationship(
                $this->buildSharedCartsResource(
                    $restSharedCartsAttributesTransfer->getIdCompanyUser(),
                    $restSharedCartsAttributesTransfer
                )
            );
        }
    }

    /**
     * @param string $uuid
     * @param \Generated\Shared\Transfer\RestSharedCartsAttributesTransfer $restSharedCartsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function buildSharedCartsResource(string $uuid, RestSharedCartsAttributesTransfer $restSharedCartsAttributesTransfer): RestResourceInterface
    {
        return $this->restResourceBuilder->createRestResource(
            SharedCartsRestApiConfig::RESOURCE_SHARED_CARTS,
            $uuid,
            $restSharedCartsAttributesTransfer
        );
    }
}
