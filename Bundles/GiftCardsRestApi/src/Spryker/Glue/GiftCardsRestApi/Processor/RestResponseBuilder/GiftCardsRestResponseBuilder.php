<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GiftCardsRestApi\Processor\RestResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\RestGiftCardsAttributesTransfer;
use Spryker\Glue\GiftCardsRestApi\GiftCardsRestApiConfig;
use Spryker\Glue\GiftCardsRestApi\Processor\Mapper\GiftCardsMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class GiftCardsRestResponseBuilder implements GiftCardsRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\GiftCardsRestApi\Processor\Mapper\GiftCardsMapperInterface
     */
    private $giftCardsMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\GiftCardsRestApi\Processor\Mapper\GiftCardsMapperInterface $giftCardsMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        GiftCardsMapperInterface $giftCardsMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->giftCardsMapper = $giftCardsMapper;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\GiftCardTransfer[] $giftCardTransfers
     * @param string $quoteReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createGiftCardsRestResource(ArrayObject $giftCardTransfers, string $quoteReference): RestResourceInterface
    {
        foreach ($giftCardTransfers as $giftCardTransfer) {
            $restGiftCardsAttributesTransfer = $this->giftCardsMapper
                ->mapGiftCardTransferToRestGiftCardsAttributesTransfer(
                    $giftCardTransfer,
                    new RestGiftCardsAttributesTransfer()
                );

            $restResource = $this->restResourceBuilder->createRestResource(
                GiftCardsRestApiConfig::RESOURCE_GIFT_CARDS,
                $giftCardTransfer->getCode(),
                $restGiftCardsAttributesTransfer
            );

            $restResource->addLink(
                RestLinkInterface::LINK_SELF,
                $this->getMerchantsOpeningHoursResourceSelfLink($quoteReference)
            );

            return $restResource;
        }
    }

    /**
     * @param string $quoteReference
     *
     * @return string
     */
    protected function getMerchantsOpeningHoursResourceSelfLink(string $quoteReference): string
    {
        return sprintf(
            '%s/%s/%s',
            GiftCardsRestApiConfig::RESOURCE_CART,
            $quoteReference,
            GiftCardsRestApiConfig::RESOURCE_GIFT_CARDS
        );
    }
}
