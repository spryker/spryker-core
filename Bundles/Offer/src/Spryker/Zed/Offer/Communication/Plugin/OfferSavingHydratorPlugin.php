<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Communication\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
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

        $quoteTransferClone = clone $offerTransfer->getQuote();
        $quoteTransferClone->setItems(new ArrayObject());

        foreach ($offerTransfer->getQuote()->getItems() as $itemTransfer) {
            $itemTransfer->setForcedUnitGrossPrice(false);
            $quoteTransferClone->getItems()->append(clone $itemTransfer);
        }

        $quoteTransfer = $cartFacade->reloadItems($quoteTransferClone);

        $originalItems = (array)$offerTransfer->getQuote()->getItems();
        $reloadedItems = (array)$quoteTransfer->getItems();

        $amount = count($originalItems);
        for ($i = 0; $i < $amount; $i++) {
            //TODO: change to unitPrice probably, fix item price editing
            $originalItems[$i]->setSaving($reloadedItems[$i]->getUnitGrossPrice() - $originalItems[$i]->getUnitGrossPrice());
        }

        return $offerTransfer;
    }
}
