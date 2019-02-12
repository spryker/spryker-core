<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Checkout\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;

class QuoteProceedCheckoutChecker implements QuoteProceedCheckoutCheckerInterface
{
    /**
     * @var \Spryker\Client\CheckoutExtension\Dependency\Plugin\QuoteProceedCheckoutCheckPluginInterface[]
     */
    protected $quoteProccedCheckoutCheckPlugins;

    /**
     * @param \Spryker\Client\CheckoutExtension\Dependency\Plugin\QuoteProceedCheckoutCheckPluginInterface[] $quoteProccedCheckoutCheckPlugins
     */
    public function __construct(array $quoteProccedCheckoutCheckPlugins)
    {
        $this->quoteProccedCheckoutCheckPlugins = $quoteProccedCheckoutCheckPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    public function isQuoteApplicableForCheckout(QuoteTransfer $quoteTransfer): QuoteValidationResponseTransfer
    {
        foreach ($this->quoteProccedCheckoutCheckPlugins as $quoteProccedCheckoutCheckPlugin) {
            $quoteValidationResponseTransfer = $quoteProccedCheckoutCheckPlugin->can($quoteTransfer);

            if (!$quoteValidationResponseTransfer->getIsSuccessful()) {
                return $quoteValidationResponseTransfer;
            }
        }

        return (new QuoteValidationResponseTransfer())
            ->setIsSuccessful(true);
    }
}
