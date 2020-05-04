<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GiftCardsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestGiftCardsAttributesTransfer;
use Spryker\Glue\GiftCardsRestApi\GiftCardsRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class GiftCardRestResponseBuilder implements GiftCardRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createGiftCardRestResource(RestResourceInterface $resource): RestResourceInterface
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer|null $payload */
        $payload = $resource->getPayload();
        foreach ($payload->getGiftCards() as $giftCardTransfer) {
            $restGiftCardsAttributesTransfer = (new RestGiftCardsAttributesTransfer())
                ->fromArray($giftCardTransfer->toArray(), true);

            $giftCardCode = $giftCardTransfer->getCode();
            $restResource = $this->restResourceBuilder->createRestResource(
                GiftCardsRestApiConfig::RESOURCE_GIFT_CARDS,
                $giftCardCode,
                $restGiftCardsAttributesTransfer
            );

            $restResource->addLink(
                RestLinkInterface::LINK_SELF,
                $this->getGiftCardsResourceSelfLink($resource->getType(), $payload->getUuid(), $giftCardCode)
            );

            return $restResource;
        }
    }

    /**
     * @param string $resourceType
     * @param string $quoteUuid
     * @param string $giftCardCode
     *
     * @return string
     */
    protected function getGiftCardsResourceSelfLink(string $resourceType, string $quoteUuid, string $giftCardCode): string
    {
        return sprintf(
            '%s/%s/%s/%s',
            $resourceType,
            $quoteUuid,
            GiftCardsRestApiConfig::RESOURCE_CART_CODES,
            $giftCardCode
        );
    }
}
