<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Communication\Plugin\SalesPayment;

use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesPaymentExtension\Dependency\Plugin\PaymentMapKeyBuilderStrategyPluginInterface;

/**
 * @method \Spryker\Zed\GiftCard\Business\GiftCardFacadeInterface getFacade()
 * @method \Spryker\Zed\GiftCard\GiftCardConfig getConfig()
 * @method \Spryker\Zed\GiftCard\Persistence\GiftCardQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\GiftCard\Communication\GiftCardCommunicationFactory getFactory()
 */
class GiftCardPaymentMapKeyBuilderStrategyPlugin extends AbstractPlugin implements PaymentMapKeyBuilderStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns true if `PaymentTransfer.giftCard` and `PaymentTransfer.giftCard.idGiftCard` are set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return bool
     */
    public function isApplicable(PaymentTransfer $paymentTransfer): bool
    {
        return $paymentTransfer->getGiftCard() && $paymentTransfer->getGiftCard()->getIdGiftCard();
    }

    /**
     * {@inheritDoc}
     * - Returns payment map key based on `PaymentTransfer.paymentProvider`, `PaymentTransfer.paymentMethod` and `PaymentTransfer.giftCard.idGiftCard`.
     * - Requires `PaymentTransfer.paymentProvider`, `PaymentTransfer.paymentMethod` and `PaymentTransfer.giftCard.idGiftCard` to be set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return string
     */
    public function buildPaymentMapKey(PaymentTransfer $paymentTransfer): string
    {
        return $this->getFacade()->buildPaymentMapKey($paymentTransfer);
    }
}
