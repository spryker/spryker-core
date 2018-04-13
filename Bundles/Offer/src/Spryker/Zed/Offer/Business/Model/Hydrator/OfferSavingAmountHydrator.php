<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business\Model\Hydrator;

use ArrayObject;
use Generated\Shared\Transfer\OfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Offer\Dependency\Facade\OfferToCartFacadeInterface;
use Spryker\Zed\Offer\Dependency\Facade\OfferToMessengerFacadeInterface;

class OfferSavingAmountHydrator implements OfferSavingAmountHydratorInterface
{
    /**
     * @var \Spryker\Zed\Offer\Dependency\Facade\OfferToCartFacadeInterface
     */
    protected $cartFacade;

    /**
     * @var \Spryker\Zed\Offer\Dependency\Facade\OfferToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**a
     *
     * @param \Spryker\Zed\Offer\Dependency\Facade\OfferToCartFacadeInterface $cartFacade
     * @param \Spryker\Zed\Offer\Dependency\Facade\OfferToMessengerFacadeInterface $messengerFacade
     */
    public function __construct(
        OfferToCartFacadeInterface $cartFacade,
        OfferToMessengerFacadeInterface $messengerFacade
    ) {
        $this->cartFacade = $cartFacade;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function hydrate(OfferTransfer $offerTransfer): OfferTransfer
    {
        $quoteTransfer = $offerTransfer->getQuote();

        $originalPriceQuoteTransfer = $this->getQuoteWithReloadedItemPrices($quoteTransfer);
        $skuOriginalPrice = $this->getOriginalPriceBySku($originalPriceQuoteTransfer);

        $this->hydrateItemsWithSavingAmount($quoteTransfer, $skuOriginalPrice);

        $this->messengerFacade->getStoredMessages();

        return $offerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function getQuoteWithReloadedItemPrices(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $originalPriceQuoteTransfer = clone $quoteTransfer;
        $originalPriceQuoteTransfer->setItems(new ArrayObject());

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $originalPriceItemTransfer = clone $itemTransfer;
            $originalPriceItemTransfer->setForcedUnitGrossPrice(false);

            $originalPriceQuoteTransfer
                ->getItems()
                ->append($originalPriceItemTransfer);
        }

        return $this->cartFacade->reloadItems($originalPriceQuoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $originalPriceQuoteTransfer
     *
     * @return array
     */
    protected function getOriginalPriceBySku(QuoteTransfer $originalPriceQuoteTransfer): array
    {
        $skuOriginalPrice = [];

        foreach ($originalPriceQuoteTransfer->getItems() as $originalPriceItemTransfer) {
            $skuOriginalPrice[$originalPriceItemTransfer->getSku()] = $originalPriceItemTransfer->getSumGrossPrice();
        }

        return $skuOriginalPrice;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $skuOriginalPrice
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function hydrateItemsWithSavingAmount(QuoteTransfer $quoteTransfer, array $skuOriginalPrice): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!isset($skuOriginalPrice[$itemTransfer->getSku()])) {
                $itemTransfer->setSaving(0);
                continue;
            }

            $savingAmount = $skuOriginalPrice[$itemTransfer->getSku()] - $itemTransfer->getSumSubtotalAggregation();
            $itemTransfer->setSaving($savingAmount);
        }

        return $quoteTransfer;
    }
}
