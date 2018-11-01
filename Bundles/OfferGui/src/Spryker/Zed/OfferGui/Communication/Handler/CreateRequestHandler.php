<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication\Handler;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
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
    public function addItems(OfferTransfer $offerTransfer): OfferTransfer
    {
        $quoteTransfer = $offerTransfer->getQuote();
        $quoteTransfer = $this->addIncomingItemsToCart($quoteTransfer);
        $offerTransfer->setQuote($quoteTransfer);

        return $offerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function addIncomingItemsToCart(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getIncomingItems() as $itemTransfer) {
            if (!$itemTransfer->getSku() || !$itemTransfer->getQuantity()) {
                continue;
            }
            $cartChangeTransfer = (new CartChangeTransfer())
                ->setQuote($quoteTransfer)
                ->addItem($itemTransfer);

            $quoteTransfer = $this->cartFacade
                ->add($cartChangeTransfer);
        }

        return $quoteTransfer;
    }
}
