<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardBalance\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\GiftCardTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\GiftCardBalance\Business\GiftCardBalanceBusinessFactory getFactory()
 */
class GiftCardBalanceFacade extends AbstractFacade implements GiftCardBalanceFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     *
     * @return int
     */
    public function getRemainingValue(GiftCardTransfer $giftCardTransfer)
    {
        return $this->getFactory()
            ->createGiftCardBalanceChecker()
            ->getRemainingValue($giftCardTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     *
     * @return bool
     */
    public function hasPositiveBalance(GiftCardTransfer $giftCardTransfer)
    {
        return $this->getFactory()
            ->createGiftCardBalanceChecker()
            ->hasPositiveBalance($giftCardTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveTransactionLog(PaymentTransfer $paymentTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getFactory()
            ->createGiftCardBalanceSaver()
            ->saveTransactionLog($paymentTransfer, $checkoutResponse);
    }
}
