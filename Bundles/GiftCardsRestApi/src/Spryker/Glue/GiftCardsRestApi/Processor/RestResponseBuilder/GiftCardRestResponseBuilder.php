<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GiftCardsRestApi\Processor\RestResponseBuilder;

use ArrayObject;
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
     * @param \ArrayObject|\Generated\Shared\Transfer\GiftCardTransfer[] $giftCardTransfers
     * @param string $quoteReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createGiftCardRestResource(ArrayObject $giftCardTransfers, string $quoteReference): RestResourceInterface
    {
        foreach ($giftCardTransfers as $giftCardTransfer) {
            $restGiftCardsAttributesTransfer = (new RestGiftCardsAttributesTransfer())
                ->fromArray($giftCardTransfer->toArray(), true);

            $restResource = $this->restResourceBuilder->createRestResource(
                GiftCardsRestApiConfig::RESOURCE_GIFT_CARDS,
                $giftCardTransfer->getCode(),
                $restGiftCardsAttributesTransfer
            );

            $restResource->addLink(
                RestLinkInterface::LINK_SELF,
                $this->getGiftCardsResourceSelfLink($quoteReference)
            );

            return $restResource;
        }
    }

    /**
     * @param string $quoteReference
     *
     * @return string
     */
    protected function getGiftCardsResourceSelfLink(string $quoteReference): string
    {
        return sprintf(
            '%s/%s/%s',
            GiftCardsRestApiConfig::RESOURCE_CARTS,
            $quoteReference,
            GiftCardsRestApiConfig::RESOURCE_GIFT_CARDS
        );
    }
}
