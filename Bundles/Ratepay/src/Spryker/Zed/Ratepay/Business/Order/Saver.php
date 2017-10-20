<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Order;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Ratepay\Persistence\SpyPaymentRatepay;
use Spryker\Zed\Ratepay\Business\Order\MethodMapper\PaymentMethodMapperInterface;

class Saver implements SaverInterface
{
    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @var \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     */
    protected $checkoutResponseTransfer;

    /**
     * @var \Spryker\Zed\Ratepay\Business\Order\MethodMapper\PaymentMethodMapperInterface $paymentMapper
     */
    protected $paymentMapper;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param \Spryker\Zed\Ratepay\Business\Order\MethodMapper\PaymentMethodMapperInterface $paymentMapper
     */
    public function __construct(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer,
        PaymentMethodMapperInterface $paymentMapper
    ) {

        $this->quoteTransfer = $quoteTransfer;
        $this->checkoutResponseTransfer = $checkoutResponseTransfer;
        $this->paymentMapper = $paymentMapper;
    }

    /**
     * @return void
     */
    public function saveOrderPayment()
    {
        $paymentEntity = new SpyPaymentRatepay();
        $idSalesOrder = $this->checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder();
        $paymentEntity->setFkSalesOrder($idSalesOrder);

        $this->paymentMapper->mapMethodDataToPayment($this->quoteTransfer, $paymentEntity);
        $paymentEntity->save();
    }
}
