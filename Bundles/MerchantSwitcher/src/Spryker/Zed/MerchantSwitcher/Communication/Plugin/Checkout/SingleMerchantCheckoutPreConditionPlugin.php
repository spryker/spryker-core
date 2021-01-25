<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSwitcher\Communication\Plugin\Checkout;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SingleMerchantQuoteValidationRequestTransfer;
use Spryker\Zed\CheckoutExtension\Dependency\Plugin\CheckoutPreConditionPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantSwitcher\MerchantSwitcherConfig getConfig()
 * @method \Spryker\Zed\MerchantSwitcher\Business\MerchantSwitcherFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantSwitcher\Communication\MerchantSwitcherCommunicationFactory getFactory()
 */
class SingleMerchantCheckoutPreConditionPlugin extends AbstractPlugin implements CheckoutPreConditionPluginInterface
{
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

        $singleMerchantQuoteValidationRequestTransfer = (new SingleMerchantQuoteValidationRequestTransfer())
            ->setItems($quoteTransfer->getItems())
            ->setMerchantReference($quoteTransfer->getMerchantReference());

        $singleMerchantQuoteValidationResponseTransfer = $this->getFacade()
            ->validateMerchantInQuoteItems($singleMerchantQuoteValidationRequestTransfer);

        $validationPassed = true;
        foreach ($singleMerchantQuoteValidationResponseTransfer->getErrors() as $messageTransfer) {
            $checkoutErrorTransfer = (new CheckoutErrorTransfer())
                ->setMessage($messageTransfer->getValue())
                ->setParameters($messageTransfer->getParameters());

            $checkoutResponseTransfer->addError($checkoutErrorTransfer);
            $validationPassed = false;
        }

        if (!$validationPassed) {
            $checkoutResponseTransfer->setIsSuccess(false);
        }

        return $validationPassed;
    }
}
