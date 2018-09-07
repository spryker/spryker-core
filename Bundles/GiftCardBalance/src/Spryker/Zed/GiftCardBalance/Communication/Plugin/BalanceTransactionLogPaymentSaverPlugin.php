<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardBalance\Communication\Plugin;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardPaymentSaverPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\GiftCardBalance\Business\GiftCardBalanceFacadeInterface getFacade()
 * @method \Spryker\Zed\GiftCardBalance\Communication\GiftCardBalanceCommunicationFactory getFactory()
 */
class BalanceTransactionLogPaymentSaverPlugin extends AbstractPlugin implements GiftCardPaymentSaverPluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function savePayment(PaymentTransfer $paymentTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getFacade()->saveTransactionLog($paymentTransfer, $checkoutResponse);
    }
}
