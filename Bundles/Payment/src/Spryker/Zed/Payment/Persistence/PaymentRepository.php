<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Persistence;

use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderCollectionTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Payment\Persistence\PaymentPersistenceFactory getFactory()
 */
class PaymentRepository extends AbstractRepository implements PaymentRepositoryInterface
{
    /**
     * @param int $idPaymentMethod
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer|null
     */
    public function findPaymentMethodById(int $idPaymentMethod): ?PaymentMethodTransfer
    {
        $paymentMethodEntity = $this->getFactory()
            ->createPaymentMethodQuery()
            ->filterByIdPaymentMethod($idPaymentMethod)
            ->findOne();

        if ($paymentMethodEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createPaymentMapper()
            ->mapPaymentMethodEntityToPaymentMethodTransfer($paymentMethodEntity, new PaymentMethodTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer|null
     */
    public function findPaymentMethod(PaymentMethodTransfer $paymentMethodTransfer): ?PaymentMethodTransfer
    {
        $paymentMethodQuery = $this->getFactory()->createPaymentMethodQuery();

        if ($paymentMethodTransfer->getIdPaymentMethod() !== null) {
            $paymentMethodQuery->filterByIdPaymentMethod($paymentMethodTransfer->getIdPaymentMethod());
        }

        if ($paymentMethodTransfer->getPaymentMethodKey() !== null) {
            $paymentMethodQuery->filterByPaymentMethodKey($paymentMethodTransfer->getPaymentMethodKey());
        }

        $paymentMethodEntity = $paymentMethodQuery->findOne();

        if (!$paymentMethodEntity) {
            return null;
        }

        return $this->getFactory()
            ->createPaymentMapper()
            ->mapPaymentMethodEntityToPaymentMethodTransfer($paymentMethodEntity, new PaymentMethodTransfer());
    }

    /**
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\PaymentProviderCollectionTransfer
     */
    public function getAvailablePaymentProvidersForStore(string $storeName): PaymentProviderCollectionTransfer
    {
        $paymentProviderCollectionTransfer = new PaymentProviderCollectionTransfer();
        $paymentProviderEntities = $this->getFactory()
            ->createPaymentProviderQuery()
            ->joinWithSpyPaymentMethod()
            ->useSpyPaymentMethodQuery()
                ->joinWithSpyPaymentMethodStore()
                ->filterByIsActive(true)
                ->useSpyPaymentMethodStoreQuery()
                    ->joinWithSpyStore()
                    ->useSpyStoreQuery()
                        ->filterByName($storeName)
                    ->endUse()
                ->endUse()
            ->endUse()
            ->groupByIdPaymentProvider()
            ->find();

        if (!$paymentProviderEntities->getData()) {
            return $paymentProviderCollectionTransfer;
        }

        return $this->getFactory()
            ->createPaymentProviderMapper()->mapPaymentProviderEntityCollectionToPaymentProviderCollectionTransfer(
                $paymentProviderEntities,
                $paymentProviderCollectionTransfer,
            );
    }

    /**
     * @param int $idPaymentMethod
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function getStoreRelationByIdPaymentMethod(int $idPaymentMethod): StoreRelationTransfer
    {
        $shipmentMethodStoreEntities = $this->getFactory()
            ->createPaymentMethodStoreQuery()
            ->filterByFkPaymentMethod($idPaymentMethod)
            ->leftJoinWithSpyStore()
            ->find();

        $storeRelationTransfer = (new StoreRelationTransfer())->setIdEntity($idPaymentMethod);

        return $this->getFactory()
            ->createStoreRelationMapper()
            ->mapPaymentMethodStoreEntitiesToStoreRelationTransfer($shipmentMethodStoreEntities, $storeRelationTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function getPaymentMethodsWithStoreRelation(): PaymentMethodsTransfer
    {
        $paymentMethodsTransfer = new PaymentMethodsTransfer();
        $paymentMethodQuery = $this->getFactory()
            ->createPaymentMethodQuery()
            ->leftJoinWithSpyPaymentMethodStore();

        if ($this->getFactory()->createPaymentMethodQuery()->getTableMap()->hasColumn('is_hidden')) {
            $paymentMethodQuery->filterByIsHidden(false);
        }
        $paymentMethodEntities = $paymentMethodQuery->find();

        if (!$paymentMethodEntities->getData()) {
            return $paymentMethodsTransfer;
        }

        return $this->getFactory()
            ->createPaymentMapper()
            ->mapPaymentMethodEntityCollectionToPaymentMethodsTransfer(
                $paymentMethodEntities,
                $paymentMethodsTransfer,
            );
    }

    /**
     * @param string $paymentProviderKey
     *
     * @return \Generated\Shared\Transfer\PaymentProviderTransfer|null
     */
    public function findPaymentProviderByKey(string $paymentProviderKey): ?PaymentProviderTransfer
    {
        $paymentProviderEntity = $this->getFactory()
            ->createPaymentProviderQuery()
            ->filterByPaymentProviderKey($paymentProviderKey)
            ->findOne();

        if ($paymentProviderEntity === null) {
            return null;
        }

        return $this->getFactory()
            ->createPaymentProviderMapper()
            ->mapPaymentProviderEntityToPaymentProviderTransfer(
                $paymentProviderEntity,
                new PaymentProviderTransfer(),
            );
    }
}
