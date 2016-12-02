<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Checkout;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class ProductBundleCheckoutAvailability
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function checkCondition(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $groupedItemQuantities = $this->groupItemsBySku($quoteTransfer->getItems());

        foreach ($groupedItemQuantities as $sku => $quantity) {
            if ($this->isProductSellable($sku, $quantity) === true) {
                continue;
            }
            $this->addAvailabilityErrorToCheckoutResponse($checkoutResponse);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    protected function addAvailabilityErrorToCheckoutResponse(CheckoutResponseTransfer $checkoutResponse)
    {
        $checkoutErrorTransfer = $this->createCheckoutErrorTransfer();
        $checkoutErrorTransfer
            ->setErrorCode($this->availabilityConfig->getProductUnavailableErrorCode())
            ->setMessage('product.unavailable');

        $checkoutResponse
            ->addError($checkoutErrorTransfer)
            ->setIsSuccess(false);
    }
}
