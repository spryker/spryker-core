<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Checkout\Quote;

use Generated\Shared\Transfer\CanProceedCheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

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
     * @return \Generated\Shared\Transfer\CanProceedCheckoutResponseTransfer
     */
    public function isQuoteApplicableForCheckout(QuoteTransfer $quoteTransfer): CanProceedCheckoutResponseTransfer
    {
        foreach ($this->quoteProccedCheckoutCheckPlugins as $quoteProccedCheckoutCheckPlugin) {
            $canProceedCheckoutResponseTransfer = $quoteProccedCheckoutCheckPlugin->can($quoteTransfer);

            if (!$canProceedCheckoutResponseTransfer->getIsSuccessful()) {
                return $canProceedCheckoutResponseTransfer;
            }
        }

        return (new CanProceedCheckoutResponseTransfer())
            ->setIsSuccessful(true);
    }
}
