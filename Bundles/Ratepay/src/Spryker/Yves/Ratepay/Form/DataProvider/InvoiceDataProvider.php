<?php
/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Yves\Ratepay\Form\DataProvider;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayPaymentInvoiceTransfer;
use Spryker\Yves\Checkout\Dependency\DataProvider\DataProviderInterface;

class InvoiceDataProvider extends DataProviderAbstract implements DataProviderInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function setRatepayPaymentTransfer(QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getPayment()->getRatepayInvoice() === null) {
            $quoteTransfer->getPayment()->setRatepayInvoice(new RatepayPaymentInvoiceTransfer());
        }
        $this->fillPaymentPhoneFromCustomer($quoteTransfer->getPayment()->getRatepayInvoice(), $quoteTransfer);
    }

}
