<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Persistence;

use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Orm\Zed\Payment\Persistence\SpyPaymentMethod;
use Orm\Zed\Payment\Persistence\SpyPaymentMethodStore;
use Orm\Zed\Payment\Persistence\SpyPaymentProvider;
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
     * @param array<int> $idStores
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
     * @param array<int> $idStores
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

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentProviderTransfer
     */
    public function createPaymentProvider(PaymentProviderTransfer $paymentProviderTransfer): PaymentProviderTransfer
    {
        $paymentProviderEntity = $this->getFactory()
            ->createPaymentProviderMapper()
            ->mapPaymentProviderTransferToPaymentProviderEntity($paymentProviderTransfer, (new SpyPaymentProvider()));

        $paymentProviderEntity->save();

        return $this->getFactory()
            ->createPaymentProviderMapper()
            ->mapPaymentProviderEntityToPaymentProviderTransfer($paymentProviderEntity, (new PaymentProviderTransfer()));
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function createPaymentMethod(PaymentMethodTransfer $paymentMethodTransfer): PaymentMethodTransfer
    {
        $paymentMethodEntity = $this->getFactory()
            ->createPaymentMapper()
            ->mapPaymentMethodTransferToPaymentMethodEntity($paymentMethodTransfer, (new SpyPaymentMethod()));

        $paymentMethodEntity->save();

        return $this->getFactory()
            ->createPaymentMapper()
            ->mapPaymentMethodEntityToPaymentMethodTransfer($paymentMethodEntity, (new PaymentMethodTransfer()));
    }
}
