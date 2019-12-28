<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Communication\Plugin\Checkout;

use ArrayObject;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreConditionPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantProductOffer\Business\MerchantProductOfferFacadeInterface getFacade()
 */
class MerchantProductOfferCheckoutPreCheckPlugin extends AbstractPlugin implements CheckoutPreConditionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns `false` response if at least one quote item transfer has product offer with inactive merchant.
     * - Sets error messages to checkout response, in case if items contains inactive merchants product offers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkCondition(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        $messageTransfers = $this->getFacade()->validateItems($quoteTransfer->getItems()->getArrayCopy());

        $checkoutErrorTransfers = [];

        foreach ($messageTransfers as $messageTransfer) {
            $checkoutErrorTransfers[] = (new CheckoutErrorTransfer())
                ->setMessage($messageTransfer->getValue())
                ->setParameters($messageTransfer->getParameters());
        }

        $checkoutResponseTransfer
            ->setErrors(new ArrayObject($checkoutErrorTransfers))
            ->setIsSuccess(!$messageTransfers);

        return !$messageTransfers;
    }
}
