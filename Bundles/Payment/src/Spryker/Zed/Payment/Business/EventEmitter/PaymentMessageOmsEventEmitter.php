<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\EventEmitter;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Payment\Business\Exception\InvalidPaymentEventException;
use Spryker\Zed\Payment\Dependency\Facade\PaymentToOmsFacadeInterface;
use Spryker\Zed\Payment\PaymentConfig;

class PaymentMessageOmsEventEmitter implements PaymentMessageOmsEventEmitterInterface
{
    /**
     * @var \Spryker\Zed\Payment\Dependency\Facade\PaymentToOmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @var \Spryker\Zed\Payment\PaymentConfig
     */
    protected $paymentConfig;

    /**
     * @param \Spryker\Zed\Payment\Dependency\Facade\PaymentToOmsFacadeInterface $omsFacade
     * @param \Spryker\Zed\Payment\PaymentConfig $paymentConfig
     */
    public function __construct(PaymentToOmsFacadeInterface $omsFacade, PaymentConfig $paymentConfig)
    {
        $this->omsFacade = $omsFacade;
        $this->paymentConfig = $paymentConfig;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $orderPaymentEventTransfer
     *
     * @throws \Spryker\Zed\Payment\Business\Exception\InvalidPaymentEventException
     *
     * @return void
     */
    public function triggerPaymentMessageOmsEvent(TransferInterface $orderPaymentEventTransfer): void
    {
        $orderPaymentEventTransferClassName = get_class($orderPaymentEventTransfer);

        if (!isset($this->paymentConfig->getSupportedOrderPaymentEventTransfersList()[$orderPaymentEventTransferClassName])) {
            throw new InvalidPaymentEventException(
                'transfer: ' . $orderPaymentEventTransferClassName . 'cannot be handled for OMS event',
            );
        }

        $this->omsFacade->triggerEventForOrderItems(
            $this->paymentConfig->getSupportedOrderPaymentEventTransfersList()[$orderPaymentEventTransferClassName],
            $orderPaymentEventTransfer->getOrderItemIds(),
            [],
        );
    }
}
