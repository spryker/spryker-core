<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Communication\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Kernel\Locator;
use Spryker\Zed\Messenger\Business\MessengerFacadeInterface;
use Spryker\Zed\OfferExtension\Dependency\Plugin\OfferHydratorPluginInterface;

/**
 * @method \Spryker\Zed\Offer\Business\OfferFacadeInterface getFacade()
 * @method \Spryker\Zed\Offer\Communication\OfferCommunicationFactory getFactory()
 */
class OfferSavingHydratorPlugin extends AbstractPlugin implements OfferHydratorPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function hydrateOffer(OfferTransfer $offerTransfer): OfferTransfer
    {
        $cartFacade = $this->getFactory()->getCartFacade();

        $quoteTransfer = $offerTransfer->getQuote();

        $originalPriceQuoteTransfer = clone $quoteTransfer;
        $originalPriceQuoteTransfer->setItems(new ArrayObject());

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $originalPriceItemTransfer = clone $itemTransfer;
            $originalPriceItemTransfer->setForcedUnitGrossPrice(false);

            $originalPriceQuoteTransfer
                ->getItems()
                ->append($originalPriceItemTransfer);
        }

        $originalPriceQuoteTransfer = $cartFacade->reloadItems($originalPriceQuoteTransfer);

        $skuOriginalPrice = [];
        foreach ($originalPriceQuoteTransfer->getItems() as $originalPriceItemTransfer) {
            $skuOriginalPrice[$originalPriceItemTransfer->getSku()] = $originalPriceItemTransfer->getSumGrossPrice();
        }

        foreach ($quoteTransfer->getItems() as $itemTransfer) {

            if (!isset($skuOriginalPrice[$itemTransfer->getSku()])) {
                $itemTransfer->setSaving(0);
                continue;
            }

            $savingAmount = $skuOriginalPrice[$itemTransfer->getSku()] - $itemTransfer->getSumSubtotalAggregation();
            $itemTransfer->setSaving($savingAmount);
        }

        $this->getFactory()->getMessengerFacade()->getStoredMessages();

        return $offerTransfer;
    }
}
