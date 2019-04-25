<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Relationship;

use Generated\Shared\Transfer\RestSharedCartsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\SharedCartReaderInterface;
use Spryker\Glue\SharedCartsRestApi\SharedCartsRestApiConfig;

class SharedCartExpander implements SharedCartExpanderInterface
{
    public const KEY_UUID = 'KEY_UUID';

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\SharedCartReaderInterface
     */
    protected $sharedCartReader;

    /**
     * @param \Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\SharedCartReaderInterface $sharedCartReader
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        SharedCartReaderInterface $sharedCartReader,
        RestResourceBuilderInterface $restResourceBuilder
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->sharedCartReader = $sharedCartReader;
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
            $restSharedCartsAttributesTransfers = $this->sharedCartReader->getSharedCartsByCartUuid($resource);
            if ($restSharedCartsAttributesTransfers) {
                $this->addSharedCartRelationship($resource, $restSharedCartsAttributesTransfers);
            }
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     * @param \Generated\Shared\Transfer\RestSharedCartsAttributesTransfer[] $restSharedCartsAttributesTransfers
     *
     * @return void
     */
    protected function addSharedCartRelationship(RestResourceInterface $resource, array $restSharedCartsAttributesTransfers): void
    {
        foreach ($restSharedCartsAttributesTransfers as $restSharedCartsAttributesTransfer) {
            $restResource = $this->buildSharedCartsResource($resource->getId(), $restSharedCartsAttributesTransfer);
            $resource->addRelationship($restResource);
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
        $restResource = $this->restResourceBuilder->createRestResource(
            SharedCartsRestApiConfig::RESOURCE_SHARED_CARTS,
            $uuid,
            $restSharedCartsAttributesTransfer
        );

        return $restResource;
    }
}
