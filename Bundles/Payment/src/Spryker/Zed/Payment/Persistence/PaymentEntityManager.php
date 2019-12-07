<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Persistence;

use Generated\Shared\Transfer\PaymentMethodTransfer;
use Orm\Zed\Payment\Persistence\SpyPaymentMethodStore;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Payment\Persistence\PaymentPersistenceFactory getFactory()
 */
class PaymentEntityManager extends AbstractEntityManager implements PaymentEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer|null
     */
    public function updatePaymentMethod(
        PaymentMethodTransfer $paymentMethodTransfer
    ): ?PaymentMethodTransfer {
        $paymentMethodTransfer->requireIdPaymentMethod();

        $paymentMethodEntity = $this->getFactory()
            ->createPaymentMethodQuery()
            ->filterByIdPaymentMethod($paymentMethodTransfer->getIdPaymentMethod())
            ->findOne();

        if ($paymentMethodEntity === null) {
            return null;
        }

        $paymentMethodMapper = $this->getFactory()->createPaymentMapper();

        $paymentMethodEntity = $paymentMethodMapper->mapPaymentMethodTransferToPaymentMethodEntity(
            $paymentMethodTransfer,
            $paymentMethodEntity
        );
        $paymentMethodEntity->save();

        return $paymentMethodMapper->mapPaymentMethodEntityToPaymentMethodTransfer(
            $paymentMethodEntity,
            $paymentMethodTransfer
        );
    }

    /**
     * @param int[] $idStores
     * @param int $idPaymentMethod
     *
     * @return void
     */
    public function addPaymentMethodStoreRelationsForStores(
        array $idStores,
        int $idPaymentMethod
    ): void {
        foreach ($idStores as $idStore) {
            $shipmentMethodStoreEntity = new SpyPaymentMethodStore();
            $shipmentMethodStoreEntity->setFkStore($idStore)
                ->setFkPaymentMethod($idPaymentMethod)
                ->save();
        }
    }

    /**
     * @param int[] $idStores
     * @param int $idPaymentMethod
     *
     * @return void
     */
    public function removePaymentMethodStoreRelationsForStores(
        array $idStores,
        int $idPaymentMethod
    ): void {
        if ($idStores === []) {
            return;
        }

        $this->getFactory()
            ->createPaymentMethodStoreQuery()
            ->filterByFkPaymentMethod($idPaymentMethod)
            ->filterByFkStore_In($idStores)
            ->delete();
    }
}
