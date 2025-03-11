<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\PaymentApp\Persistence\Mapper;

use Generated\Shared\Transfer\PaymentAppPaymentStatusCollectionTransfer;
use Generated\Shared\Transfer\PaymentAppPaymentStatusTransfer;
use Orm\Zed\PaymentApp\Persistence\Base\SpyPaymentAppPaymentStatus;
use Propel\Runtime\Collection\Collection;

class PaymentAppPaymentStatusMapper
{
    /**
     * @param \Propel\Runtime\Collection\Collection $paymentAppPaymentStatusEntityCollection
     * @param \Generated\Shared\Transfer\PaymentAppPaymentStatusCollectionTransfer $paymentAppPaymentStatusCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentAppPaymentStatusCollectionTransfer
     */
    public function mapPaymentAppPaymentStatusEntityCollectionToTransferCollection(
        Collection $paymentAppPaymentStatusEntityCollection,
        PaymentAppPaymentStatusCollectionTransfer $paymentAppPaymentStatusCollectionTransfer
    ): PaymentAppPaymentStatusCollectionTransfer {
        foreach ($paymentAppPaymentStatusEntityCollection as $paymentAppPaymentStatusEntity) {
            $paymentAppPaymentStatusCollectionTransfer->addPaymentAppPaymentStatus(
                $this->mapPaymentAppPaymentStatusEntityToTransfer($paymentAppPaymentStatusEntity, new PaymentAppPaymentStatusTransfer()),
            );
        }

        return $paymentAppPaymentStatusCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\PaymentApp\Persistence\Base\SpyPaymentAppPaymentStatus $paymentAppPaymentStatusEntity
     * @param \Generated\Shared\Transfer\PaymentAppPaymentStatusTransfer $paymentAppPaymentStatusTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentAppPaymentStatusTransfer
     */
    protected function mapPaymentAppPaymentStatusEntityToTransfer(
        SpyPaymentAppPaymentStatus $paymentAppPaymentStatusEntity,
        PaymentAppPaymentStatusTransfer $paymentAppPaymentStatusTransfer
    ): PaymentAppPaymentStatusTransfer {
        return $paymentAppPaymentStatusTransfer->fromArray($paymentAppPaymentStatusEntity->toArray(), true);
    }
}
