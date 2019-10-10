<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Checkout\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @method \Spryker\Zed\Checkout\Business\CheckoutBusinessFactory getFactory()
 */
interface CheckoutFacadeInterface
{
    /**
     * Specification:
     * - Run checkout pre-condition plugins (rises errors)
     * - Run checkout pre-save plugins
     * - Run checkout order saver plugins (in a transaction)
     * - Trigger state machine for all items of the new order (Oms)
     * - Run checkout post-save plugins
     * - Return response with boolean isSuccess and an array of errors
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function placeOrder(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     * - Runs checkout pre-condition CheckoutPreConditionPluginInterface plugins.
     * - Returns response with boolean isSuccess and an array of errors.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function isPlaceableOrder(QuoteTransfer $quoteTransfer): CheckoutResponseTransfer;
}
