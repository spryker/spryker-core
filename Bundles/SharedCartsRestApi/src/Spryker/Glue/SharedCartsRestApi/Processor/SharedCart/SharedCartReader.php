<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SharedCartsRestApi\Processor\SharedCart;

use Spryker\Client\SharedCartsRestApi\SharedCartsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\SharedCartsRestApi\Processor\Mapper\SharedCartsResourceMapperInterface;

class SharedCartReader implements SharedCartReaderInterface
{
    /**
     * @var \Spryker\Client\SharedCartsRestApi\Dependency\Client\SharedCartsRestApiToZedRequestClientInterface
     */
    protected $sharedCartsRestApiClient;

    /**
     * @var \Spryker\Glue\SharedCartsRestApi\Processor\Mapper\SharedCartsResourceMapperInterface
     */
    protected $sharedCartsResourceMapper;

    /**
     * @param \Spryker\Client\SharedCartsRestApi\SharedCartsRestApiClientInterface $sharedCartsRestApiClient
     * @param \Spryker\Glue\SharedCartsRestApi\Processor\Mapper\SharedCartsResourceMapperInterface $sharedCartsResourceMapper
     */
    public function __construct(
        SharedCartsRestApiClientInterface $sharedCartsRestApiClient,
        SharedCartsResourceMapperInterface $sharedCartsResourceMapper
    ) {
        $this->sharedCartsRestApiClient = $sharedCartsRestApiClient;
        $this->sharedCartsResourceMapper = $sharedCartsResourceMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return \Generated\Shared\Transfer\RestSharedCartsAttributesTransfer[]
     */
    public function getSharedCartsByCartUuid(RestResourceInterface $resource): array
    {
        $shareDetailCollectionTransfer = $this->sharedCartsRestApiClient->getSharedCartsByCartUuid(
            $resource->getId()
        );

        return $this->sharedCartsResourceMapper->mapSharedCartsResource($shareDetailCollectionTransfer);
    }
}
