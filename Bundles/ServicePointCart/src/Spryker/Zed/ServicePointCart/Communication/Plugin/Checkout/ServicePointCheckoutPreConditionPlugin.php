<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointCart\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreConditionPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ServicePointCart\Business\ServicePointCartFacadeInterface getFacade()
 * @method \Spryker\Zed\ServicePointCart\ServicePointCartConfig getConfig()
 */
class ServicePointCheckoutPreConditionPlugin extends AbstractPlugin implements CheckoutPreConditionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `QuoteTransfer.items.servicePoint` to be provided.
     * - Requires `QuoteTransfer.store.name` and `QuoteTransfer.items.servicePoint.uuid` to be provided if `QuoteTransfer.items.servicePoint` is provided.
     * - Checks if `QuoteTransfer.items.servicePoint` are active and available for the current store.
     * - Sets `CheckoutResponseTransfer.isSuccess` = `false` if any of the service points is inactive or unavailable for the current store.
     * - Adds `CheckoutResponseTransfer.checkoutError` with corresponding error message if any of the service points is inactive or unavailable for the current store.
     * - Returns `true` if all service points are active and available for the current store, `false` otherwise.
     * - Returns `true` if no service points are provided in `QuoteTransfer.items`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkCondition(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): bool {
        return $this->getFacade()->validateCheckoutQuoteItems($quoteTransfer, $checkoutResponseTransfer);
    }
}
