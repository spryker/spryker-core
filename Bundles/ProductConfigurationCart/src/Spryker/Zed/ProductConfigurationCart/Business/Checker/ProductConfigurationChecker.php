<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationCart\Business\Checker;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class ProductConfigurationChecker implements ProductConfigurationCheckerInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PRODUCT_CONFIGURATION_IS_NOT_COMPLETE = 'product_configuration.checkout.validation.error.is_not_complete';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function isQuoteProductConfigurationValid(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $productConfigurationInstanceTransfer = $itemTransfer->getProductConfigurationInstance();

            if (!$productConfigurationInstanceTransfer) {
                continue;
            }

            if (!$productConfigurationInstanceTransfer->getIsComplete()) {
                $this->addCheckoutError($checkoutResponseTransfer, static::GLOSSARY_KEY_PRODUCT_CONFIGURATION_IS_NOT_COMPLETE);

                return false;
            }
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function addCheckoutError(CheckoutResponseTransfer $checkoutResponseTransfer, string $message): CheckoutResponseTransfer
    {
        $checkoutResponseTransfer
            ->addError((new CheckoutErrorTransfer())->setMessage($message))
            ->setIsSuccess(false);

        return $checkoutResponseTransfer;
    }
}
