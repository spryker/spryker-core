<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\DummyMarketplacePayment\Expander;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\DummyMarketplacePayment\DummyMarketplacePaymentConfig;
use Symfony\Component\HttpFoundation\Request;

class DummyMarketplacePaymentExpander implements DummyMarketplacePaymentExpanderInterface
{
    protected const PAYMENT_METHOD = 'Marketplace Invoice';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addPaymentToQuote(Request $request, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $quoteTransfer
            ->getPayment()
            ->setPaymentProvider(DummyMarketplacePaymentConfig::PAYMENT_PROVIDER_NAME)
            ->setPaymentMethod(static::PAYMENT_METHOD);

        return $quoteTransfer;
    }
}
