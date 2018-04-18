<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication\Handler;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToCartFacadeInterface;

class CreateRequestHandler implements CreateRequestHandlerInterface
{
    /**
     * @var \Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToCartFacadeInterface
     */
    protected $cartFacade;

    /**
     * @param \Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToCartFacadeInterface $cartFacade
     */
    public function __construct(OfferGuiToCartFacadeInterface $cartFacade)
    {
        $this->cartFacade = $cartFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function removeRedundantItems(OfferTransfer $offerTransfer): OfferTransfer
    {
        $quoteTransfer = $offerTransfer->getQuote();

        $itemTransfers = new ArrayObject();
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getQuantity() > 0) {
                $itemTransfers->append($itemTransfer);
            }
        }
        $quoteTransfer->setItems($itemTransfers);
        $offerTransfer->setQuote($quoteTransfer);

        return $offerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function addItems(OfferTransfer $offerTransfer): OfferTransfer
    {
        $quoteTransfer = $offerTransfer->getQuote();

        //add items
        $incomingItems = new ArrayObject();
        foreach ($quoteTransfer->getIncomingItems() as $itemTransfer) {
            if ($itemTransfer->getSku()) {
                $incomingItems->append($itemTransfer);
            }
        }

        foreach ($incomingItems as $itemTransfer) {
            $cartChangeTransfer = (new CartChangeTransfer())
                ->setQuote($quoteTransfer)
                ->addItem($itemTransfer);

            $quoteTransfer = $this->cartFacade
                ->add($cartChangeTransfer);
        }

        $offerTransfer->setQuote($quoteTransfer);

        return $offerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function updateCart(OfferTransfer $offerTransfer): OfferTransfer
    {
        $quoteTransfer = $offerTransfer->getQuote();
        $quoteTransfer = $this->cartFacade->reloadItems($quoteTransfer);
        $offerTransfer->setQuote($quoteTransfer);

        return $offerTransfer;
    }
}
