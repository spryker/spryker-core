<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GiftCardsRestApi\Processor\Expander;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Glue\GiftCardsRestApi\Processor\RestResponseBuilder\GiftCardsRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class GiftCardByQuoteResourceRelationshipExpander implements GiftCardByQuoteResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\GiftCardsRestApi\Processor\RestResponseBuilder\GiftCardsRestResponseBuilderInterface
     */
    protected $giftCardsRestResponseBuilder;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\GiftCardsRestApi\Processor\RestResponseBuilder\GiftCardsRestResponseBuilderInterface $giftCardsRestResponseBuilder
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        GiftCardsRestResponseBuilderInterface $giftCardsRestResponseBuilder
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->giftCardsRestResponseBuilder = $giftCardsRestResponseBuilder;
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

            $giftCardsRestResource = $this->giftCardsRestResponseBuilder
                ->createGiftCardsRestResource($giftCardTransfers, $payload->getUuid());

            $resource->addRelationship($giftCardsRestResource);
        }
    }
}
