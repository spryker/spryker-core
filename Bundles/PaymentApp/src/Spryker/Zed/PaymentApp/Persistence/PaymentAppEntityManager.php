<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\PaymentApp\Persistence;

use Generated\Shared\Transfer\PaymentAppStatusUpdatedTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\PaymentApp\Persistence\PaymentAppPersistenceFactory getFactory()
 */
class PaymentAppEntityManager extends AbstractEntityManager implements PaymentAppEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentAppStatusUpdatedTransfer $paymentAppStatusUpdatedTransfer
     *
     * @return void
     */
    public function persistPaymentAppPaymentStatus(PaymentAppStatusUpdatedTransfer $paymentAppStatusUpdatedTransfer): void
    {
        $paymentAppPaymentStatusQuery = $this->getFactory()->createPaymentAppPaymentStatusQuery();
        $paymentAppPaymentStatusEntity = $paymentAppPaymentStatusQuery
            ->filterByOrderReference($paymentAppStatusUpdatedTransfer->getOrderReferenceOrFail())
            ->findOneOrCreate();

        $paymentAppPaymentStatusEntity->setStatus($paymentAppStatusUpdatedTransfer->getStatusOrFail());
        $paymentAppPaymentStatusEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentAppStatusUpdatedTransfer $paymentAppStatusUpdatedTransfer
     *
     * @return void
     */
    public function persistPaymentAppPaymentStatusHistory(PaymentAppStatusUpdatedTransfer $paymentAppStatusUpdatedTransfer): void
    {
        $paymentAppPaymentStatusHistoryEntity = $this->getFactory()->createPaymentAppPaymentStatusHistory();
        $paymentAppPaymentStatusHistoryEntity
            ->setOrderReference($paymentAppStatusUpdatedTransfer->getOrderReferenceOrFail())
            ->setStatus($paymentAppStatusUpdatedTransfer->getStatusOrFail())
            ->setContext($paymentAppStatusUpdatedTransfer->getContext())
            ->save();
    }
}
