<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Request\Payment\Method;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayPaymentElvTransfer;
use Generated\Shared\Transfer\RatepayPaymentRequestTransfer;
use Spryker\Shared\Ratepay\RatepayConstants;

/**
 * Ratepay Elv payment method.
 */
class Elv extends AbstractMethod
{
    /**
     * @const Payment method code.
     */
    public const METHOD = RatepayConstants::METHOD_ELV;

    /**
     * @return string
     */
    public function getMethodName()
    {
        return static::METHOD;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RatepayPaymentElvTransfer
     */
    public function getPaymentData(QuoteTransfer $quoteTransfer)
    {
        return $quoteTransfer
            ->requirePayment()
            ->getPayment()
            ->requireRatepayElv()
            ->getRatepayElv();
    }

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer
     *
     * @return void
     */
    protected function mapPaymentData(RatepayPaymentRequestTransfer $ratepayPaymentRequestTransfer)
    {
        parent::mapPaymentData($ratepayPaymentRequestTransfer);
        $this->mapBankAccountData($ratepayPaymentRequestTransfer);
    }

    /**
     * @param \Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay $payment
     *
     * @return \Generated\Shared\Transfer\RatepayPaymentElvTransfer
     */
    protected function getPaymentTransferObject($payment)
    {
        return new RatepayPaymentElvTransfer();
    }
}
