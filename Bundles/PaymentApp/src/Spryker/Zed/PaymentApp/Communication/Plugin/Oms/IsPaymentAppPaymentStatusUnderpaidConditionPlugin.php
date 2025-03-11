<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\PaymentApp\Communication\Plugin\Oms;

use Generated\Shared\Transfer\PaymentAppPaymentStatusRequestTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Shared\PaymentApp\Status\PaymentStatus;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Dependency\Plugin\Condition\ConditionInterface;

/**
 * {@inheritDoc}
 *
 * @method \Spryker\Zed\PaymentApp\PaymentAppConfig getConfig()
 * @method \Spryker\Zed\PaymentApp\Business\PaymentAppFacadeInterface getFacade()
 * @method \Spryker\Zed\PaymentApp\Business\PaymentAppBusinessFactory getFactory()()
 */
class IsPaymentAppPaymentStatusUnderpaidConditionPlugin extends AbstractPlugin implements ConditionInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return bool
     */
    public function check(SpySalesOrderItem $orderItem): bool
    {
        $paymentAppPaymentStatusRequestTransfer = new PaymentAppPaymentStatusRequestTransfer();
        $paymentAppPaymentStatusRequestTransfer
            ->setOrderReference($orderItem->getOrder()->getOrderReference())
            ->setStatus(PaymentStatus::STATUS_UNDERPAID);

        $paymentAppPaymentStatusResponseTransfer = $this->getFacade()->hasPaymentAppExpectedPaymentStatus($paymentAppPaymentStatusRequestTransfer);

        return $paymentAppPaymentStatusResponseTransfer->getIsInExpectedStateOrFail();
    }
}
