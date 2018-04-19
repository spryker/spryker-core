<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business\Model\Hydrator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Zed\Offer\Dependency\Facade\OfferToMessengerFacadeInterface;
use Spryker\Zed\Offer\OfferConfig;

class OfferSavingAmountHydrator implements OfferSavingAmountHydratorInterface
{
    /**
     * @var \Spryker\Zed\Offer\Dependency\Facade\OfferToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /** @var \Spryker\Zed\Offer\OfferConfig */
    protected $offerConfig;

    /**
     * @param \Spryker\Zed\Offer\Dependency\Facade\OfferToMessengerFacadeInterface $messengerFacade
     * @param \Spryker\Zed\Offer\OfferConfig $offerConfig
     */
    public function __construct(
        OfferToMessengerFacadeInterface $messengerFacade,
        OfferConfig $offerConfig
    ) {
        $this->messengerFacade = $messengerFacade;
        $this->offerConfig = $offerConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function hydrate(OfferTransfer $offerTransfer): OfferTransfer
    {
        $quoteTransfer = $offerTransfer->getQuote();

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $saving = $this->getOriginUnitPrice($itemTransfer, $quoteTransfer->getPriceMode());
            $saving -= $this->getUnitPrice($itemTransfer, $quoteTransfer->getPriceMode());
            $saving *= $itemTransfer->getQuantity();

            $itemTransfer->setSaving($saving);
        }

        $this->messengerFacade->getStoredMessages();

        return $offerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $priceMode
     *
     * @return int
     */
    protected function getOriginUnitPrice(ItemTransfer $itemTransfer, $priceMode)
    {
        if ($priceMode === $this->offerConfig->getPriceModeGross()) {
            return $itemTransfer->getOriginUnitGrossPrice();
        }

        return $itemTransfer->getOriginUnitNetPrice();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $priceMode
     *
     * @return int
     */
    protected function getUnitPrice(ItemTransfer $itemTransfer, $priceMode)
    {
        if ($priceMode === $this->offerConfig->getPriceModeGross()) {
            return $itemTransfer->getUnitGrossPrice();
        }

        return $itemTransfer->getUnitNetPrice();
    }
}
