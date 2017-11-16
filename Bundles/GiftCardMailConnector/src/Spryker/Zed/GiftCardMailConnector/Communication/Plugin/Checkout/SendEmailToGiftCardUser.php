<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardMailConnector\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPostSaveHookInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\GiftCardMailConnector\Communication\GiftCardMailConnectorCommunicationFactory getFactory()
 * @method \Spryker\Zed\GiftCardMailConnector\Business\GiftCardMailConnectorFacadeInterface getFacade()
 */
class SendEmailToGiftCardUser extends AbstractPlugin implements CheckoutPostSaveHookInterface
{
    /**
     * Specification:
     * - Finds used for an order Gift Cards in the provided quote
     * - Sends an email to a Gift Card user
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function executeHook(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getFacade()->sendUsageNotification($quoteTransfer, $checkoutResponse);
    }
}
