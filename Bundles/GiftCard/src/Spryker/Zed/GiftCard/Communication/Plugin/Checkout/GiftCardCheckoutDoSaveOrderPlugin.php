<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutDoSaveOrderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @deprecated Use {@link \Spryker\Zed\GiftCard\Communication\Plugin\Checkout\GiftCardPaymentCheckoutDoSaveOrderPlugin}
 * and {@link \Spryker\Zed\GiftCard\Communication\Plugin\Sales\GiftCardOrderItemsPostSavePlugin} instead.
 *
 * @method \Spryker\Zed\GiftCard\Business\GiftCardFacadeInterface getFacade()
 * @method \Spryker\Zed\GiftCard\Communication\GiftCardCommunicationFactory getFactory()
 * @method \Spryker\Zed\GiftCard\GiftCardConfig getConfig()
 * @method \Spryker\Zed\GiftCard\Persistence\GiftCardQueryContainerInterface getQueryContainer()
 */
class GiftCardCheckoutDoSaveOrderPlugin extends AbstractPlugin implements CheckoutDoSaveOrderInterface
{
    /**
     * {@inheritDoc}
     * - Saves gift cards items from the quote.
     * - Saves gift card payments from the quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
        $this->getFacade()->saveOrderGiftCards($quoteTransfer, $saveOrderTransfer);
    }
}
