<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GiftCardsRestApi\Processor\Expander;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Glue\GiftCardsRestApi\Processor\RestResponseBuilder\GiftCardRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class GiftCardByQuoteResourceRelationshipExpander implements GiftCardByQuoteResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\GiftCardsRestApi\Processor\RestResponseBuilder\GiftCardRestResponseBuilderInterface
     */
    protected $giftCardRestResponseBuilder;

    /**
     * @param \Spryker\Glue\GiftCardsRestApi\Processor\RestResponseBuilder\GiftCardRestResponseBuilderInterface $giftCardRestResponseBuilder
     */
    public function __construct(
        GiftCardRestResponseBuilderInterface $giftCardRestResponseBuilder
    ) {
        $this->giftCardRestResponseBuilder = $giftCardRestResponseBuilder;
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
            /** @var \Generated\Shared\Transfer\QuoteTransfer|null $payload */
            $payload = $resource->getPayload();
            if ($payload === null || !($payload instanceof QuoteTransfer)) {
                continue;
            }

            $giftCardTransfers = $payload->getGiftCards();
            if (!count($giftCardTransfers)) {
                continue;
            }

            $giftCardsRestResource = $this->giftCardRestResponseBuilder
                ->createGiftCardRestResource($resource);

            $resource->addRelationship($giftCardsRestResource);
        }
    }
}
