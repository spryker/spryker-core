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

class GiftCardRestResponseBuilder implements GiftCardRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder)
    {
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param string $resourceType
     * @param \ArrayObject $giftCardTransfers
     * @param string $quoteUuid
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function createGiftCardRestResource(string $resourceType, ArrayObject $giftCardTransfers, string $quoteUuid): array
    {
        $giftCardResources = [];
        foreach ($giftCardTransfers as $giftCardTransfer) {
            $restGiftCardsAttributesTransfer = (new RestGiftCardsAttributesTransfer())
                ->fromArray($giftCardTransfer->toArray(), true);

            $giftCardCode = $giftCardTransfer->getCode();
            $giftCardRestResource = $this->restResourceBuilder->createRestResource(
                GiftCardsRestApiConfig::RESOURCE_GIFT_CARDS,
                $giftCardCode,
                $restGiftCardsAttributesTransfer
            );

            $giftCardRestResource->addLink(
                RestLinkInterface::LINK_SELF,
                $this->getGiftCardsResourceSelfLink($resourceType, $quoteUuid, $giftCardCode)
            );

            $giftCardResources[] = $giftCardRestResource;
        }

        return $giftCardResources;
    }

    /**
     * @param string $parentResourceType
     * @param string $quoteUuid
     * @param string $giftCardCode
     *
     * @return string
     */
    protected function getGiftCardsResourceSelfLink(
        string $parentResourceType,
        string $quoteUuid,
        string $giftCardCode
    ): string {
        return sprintf(
            '%s/%s/%s/%s',
            $parentResourceType,
            $quoteUuid,
            GiftCardsRestApiConfig::RESOURCE_CART_CODES,
            $giftCardCode
        );
    }
}
