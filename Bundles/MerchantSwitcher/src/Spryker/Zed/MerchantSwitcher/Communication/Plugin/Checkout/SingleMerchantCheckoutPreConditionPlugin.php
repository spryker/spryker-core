<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSwitcher\Communication\Plugin\Checkout;

use ArrayObject;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreConditionPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantSwitcher\MerchantSwitcherConfig getConfig()
 * @method \Spryker\Zed\MerchantSwitcher\Business\MerchantSwitcherFacadeInterface getFacade()
 */
class SingleMerchantCheckoutPreConditionPlugin extends AbstractPlugin implements CheckoutPreConditionPluginInterface
{
    protected const GLOSSARY_KEY_PRODUCT_IS_NOT_AVAILABLE = 'merchant_switcher.message.product_is_not_available';
    protected const GLOSSARY_PARAMETER_NAME = '%product_name%';

    /**
     * {@inheritDoc}
     * - Goes through QuoteTransfer.items and compares ItemTransfer.merchantReference with QuoteTransfer.merchantReference.
     * - If values are not equal the plugin returns a failure response with an error messages inside.
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
        if (!$this->getConfig()->isMerchantSwitcherEnabled()) {
            return true;
        }

        $quoteMerchantReference = $quoteTransfer->getMerchantReference();

        $checkoutErrorTransfers = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (
                $quoteMerchantReference
                && $itemTransfer->getMerchantReference()
                && $itemTransfer->getMerchantReference() !== $quoteMerchantReference
            ) {
                $checkoutErrorTransfers[] = (new CheckoutErrorTransfer())
                    ->setMessage(static::GLOSSARY_KEY_PRODUCT_IS_NOT_AVAILABLE)
                    ->setParameters([
                        static::GLOSSARY_PARAMETER_NAME => $itemTransfer->getSku(),
                    ]);
            }
        }

        $checkoutResponseTransfer
            ->setIsSuccess(!$checkoutErrorTransfers)
            ->setErrors(new ArrayObject($checkoutErrorTransfers));

        return !$checkoutErrorTransfers;
    }
}
