<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Persistence;

use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderCollectionTransfer;
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
                $paymentProviderCollectionTransfer
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
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function getActivePaymentMethodsForStore(int $idStore): PaymentMethodsTransfer
    {
        $paymentMethodsTransfer = new PaymentMethodsTransfer();
        $paymentMethodEntities = $this->getFactory()
            ->createPaymentMethodQuery()
            ->useSpyPaymentMethodStoreQuery()
                ->filterByFkStore($idStore)
            ->endUse()
            ->find();

        if (!$paymentMethodEntities->getData()) {
            return $paymentMethodsTransfer;
        }

        return $this->getFactory()
            ->createPaymentMapper()
            ->mapPaymentMethodEntityCollectionToPaymentMethodsTransfer(
                $paymentMethodEntities,
                $paymentMethodsTransfer
            );
    }
}
