<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\DummyMarketplacePayment\Form\DataProvider;

use Generated\Shared\Transfer\DummyMarketplacePaymentTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;

class InvoiceFormDataProvider implements StepEngineFormDataProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer)
    {
        if (!$quoteTransfer->getPayment()) {
            $quoteTransfer->setPayment(new PaymentTransfer());
        }

        if ($quoteTransfer->getPayment()->getDummyMarketplacePaymentInvoice()) {
            return $quoteTransfer;
        }

        $quoteTransfer->getPayment()->setDummyMarketplacePaymentInvoice(new DummyMarketplacePaymentTransfer());

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions(AbstractTransfer $quoteTransfer)
    {
        return [];
    }
}
