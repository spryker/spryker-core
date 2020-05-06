<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\DummyMarketplacePayment\Expander;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\DummyMarketplacePayment\DummyMarketplacePaymentConfig;

class DummyMarketplacePaymentExpander implements DummyMarketplacePaymentExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addPaymentToQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $quoteTransfer->getPayment()
            ->setPaymentProvider(DummyMarketplacePaymentConfig::PAYMENT_PROVIDER_NAME)
            ->setPaymentMethod('Marketplace Invoice');

        return $quoteTransfer;
    }
}
