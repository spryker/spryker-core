<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Persistence\Mapper;

use Generated\Shared\Transfer\OfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpyOfferEntityTransfer;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Zed\Offer\Dependency\Service\OfferToUtilEncodingServiceInterface;

class OfferMapper implements OfferMapperInterface
{
    /**
     * @var \Spryker\Zed\Offer\Dependency\Service\OfferToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\Offer\Dependency\Service\OfferToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(OfferToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyOfferEntityTransfer $offerEntityTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function mapOfferEntityToOffer(SpyOfferEntityTransfer $offerEntityTransfer): OfferTransfer
    {
        $offerTransfer = (new OfferTransfer())->fromArray($offerEntityTransfer->toArray(), true);
        $offerTransfer = $this->decodeQuote($offerTransfer, $offerEntityTransfer);

        return $offerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\SpyOfferEntityTransfer
     */
    public function mapOfferToOfferEntity(OfferTransfer $offerTransfer): SpyOfferEntityTransfer
    {
        $offerEntityTransfer = (new SpyOfferEntityTransfer())->fromArray($offerTransfer->toArray(), true);
        $offerEntityTransfer = $this->encodeQuote($offerTransfer, $offerEntityTransfer);

        return $offerEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     * @param \Generated\Shared\Transfer\SpyOfferEntityTransfer $offerEntityTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    protected function decodeQuote(OfferTransfer $offerTransfer, SpyOfferEntityTransfer $offerEntityTransfer)
    {
        $offerTransfer->setQuote(
            (new QuoteTransfer())
                ->fromArray(
                    $this->utilEncodingService->decodeJson($offerEntityTransfer->getQuoteData(), true),
                    true
                )
        );

        //TODO: suggest a better way to understand in post order save plugin, whether we should disable an offer.
        $offerTransfer->getQuote()->setIdOffer($offerTransfer->getIdOffer());

        return $offerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     * @param \Generated\Shared\Transfer\SpyOfferEntityTransfer $offerEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyOfferEntityTransfer
     */
    protected function encodeQuote(OfferTransfer $offerTransfer, SpyOfferEntityTransfer $offerEntityTransfer)
    {
        $offerEntityTransfer->setQuoteData(
            $this->utilEncodingService->encodeJson(
                $this->getQuoteArray($offerTransfer->getQuote())
            )
        );

        return $offerEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getQuoteArray(QuoteTransfer $quoteTransfer): array
    {
        $quoteArray = array_intersect_key(
            $quoteTransfer->toArray(),
            array_flip($this->getFieldsToPersist())
        );

        return $quoteArray;
    }

    /**
     * @return array
     */
    protected function getFieldsToPersist()
    {
        $fieldsToPersist = [
            'store',
            'items',
            'totals',
            'expenses',
            'price_mode',
            'currency',
            'billing_address',
            'shipping_address',
            'billing_same_as_shipping',
            'voucher_discounts',
            'cart_rule_discounts',
            'gift_cards',
            'payments',
            'shipment',
            'bundle_items',
            'checkout_confirmed'
        ];

        return $fieldsToPersist;
    }
}
