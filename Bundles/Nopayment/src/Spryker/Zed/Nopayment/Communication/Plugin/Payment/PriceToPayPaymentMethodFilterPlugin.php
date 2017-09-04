<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Nopayment\Communication\Plugin\Payment;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Payment\Dependency\Plugin\Payment\PaymentMethodFilterPluginInterface;

/**
 * @method \Spryker\Zed\Nopayment\Business\NopaymentFacadeInterface getFacade()
 * @method \Spryker\Zed\Nopayment\Communication\NopaymentCommunicationFactory getFactory()
 */
class PriceToPayPaymentMethodFilterPlugin extends AbstractPlugin implements PaymentMethodFilterPluginInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentInformationTransfer[]|\ArrayObject $paymentMethods
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentInformationTransfer[]|\ArrayObject
     */
    public function filterPaymentMethods(ArrayObject $paymentMethods, QuoteTransfer $quoteTransfer)
    {
        return $this->getFacade()->filterPaymentMethods($paymentMethods, $quoteTransfer);
    }

}
