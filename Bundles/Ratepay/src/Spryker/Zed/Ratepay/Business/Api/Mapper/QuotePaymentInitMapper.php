<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Mapper;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RatepayPaymentInitTransfer;
use Spryker\Zed\Ratepay\Business\Service\PaymentMethodExtractor;

class QuotePaymentInitMapper extends BaseMapper
{
    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @var \Generated\Shared\Transfer\RatepayPaymentInitTransfer
     */
    protected $ratepayPaymentInitTransfer;

    /**
     * @var \Spryker\Zed\Ratepay\Business\Service\PaymentMethodExtractor
     */
    protected $paymentMethodExtractor;

    /**
     * @param \Generated\Shared\Transfer\RatepayPaymentInitTransfer $ratepayPaymentInitTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Zed\Ratepay\Business\Service\PaymentMethodExtractor $paymentMethodExtractor
     */
    public function __construct(
        RatepayPaymentInitTransfer $ratepayPaymentInitTransfer,
        QuoteTransfer $quoteTransfer,
        PaymentMethodExtractor $paymentMethodExtractor
    ) {
        $this->ratepayPaymentInitTransfer = $ratepayPaymentInitTransfer;
        $this->quoteTransfer = $quoteTransfer;
        $this->paymentMethodExtractor = $paymentMethodExtractor;
    }

    /**
     * @return void
     */
    public function map()
    {
        if ($this->quoteTransfer->getPayment()) {
            $paymentMethodName = $this->quoteTransfer
                ->getPayment()
                ->getPaymentMethod();

            $this->ratepayPaymentInitTransfer
                ->setPaymentMethodName($paymentMethodName);
        }

        $paymentMethod = $this->paymentMethodExtractor->extractPaymentMethod($this->quoteTransfer);
        if ($paymentMethod) {
            $this->ratepayPaymentInitTransfer
                ->setTransactionId($paymentMethod->getTransactionId())
                ->setTransactionShortId($paymentMethod->getTransactionShortId())
                ->setDeviceFingerprint($paymentMethod->getDeviceFingerprint());
        }

        $this->ratepayPaymentInitTransfer
            ->setCustomerId($this->quoteTransfer->getCustomer()->getIdCustomer());
    }
}
