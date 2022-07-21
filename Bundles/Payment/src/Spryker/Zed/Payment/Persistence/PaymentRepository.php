<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Persistence;

use Generated\Shared\Transfer\PaymentMethodCollectionTransfer;
use Generated\Shared\Transfer\PaymentMethodCriteriaTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderCollectionTransfer;
use Generated\Shared\Transfer\PaymentProviderCriteriaTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery;
use Orm\Zed\Payment\Persistence\SpyPaymentProviderQuery;
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

        if (!$paymentMethodEntity) {
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
            ->createPaymentProviderMapper()
            ->mapPaymentProviderEntityCollectionToPaymentProviderCollectionTransfer(
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
        $paymentProviderTransfer = new PaymentProviderTransfer();
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
                $paymentProviderTransfer,
            );
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderCriteriaTransfer $paymentProviderCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentProviderCollectionTransfer
     */
    public function getPaymentProviderCollection(PaymentProviderCriteriaTransfer $paymentProviderCriteriaTransfer): PaymentProviderCollectionTransfer
    {
        $paymentProviderCollectionTransfer = new PaymentProviderCollectionTransfer();
        $paymentProviderQuery = $this->getFactory()
            ->createPaymentProviderQuery()
            ->leftJoinWithSpyPaymentMethod();

        $paymentProviderEntities = $this->applyPaymentProviderFilters(
            $paymentProviderQuery,
            $paymentProviderCriteriaTransfer,
        )->find();

        if (!$paymentProviderEntities->count()) {
            return $paymentProviderCollectionTransfer;
        }

        return $this->getFactory()
            ->createPaymentProviderMapper()
            ->mapPaymentProviderEntityCollectionToPaymentProviderCollectionTransfer(
                $paymentProviderEntities,
                $paymentProviderCollectionTransfer,
            );
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodCriteriaTransfer $paymentMethodCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodCollectionTransfer
     */
    public function getPaymentMethodCollection(PaymentMethodCriteriaTransfer $paymentMethodCriteriaTransfer): PaymentMethodCollectionTransfer
    {
        $paymentMethodCollectionTransfer = new PaymentMethodCollectionTransfer();
        $paymentMethodQuery = $this->getFactory()
            ->createPaymentMethodQuery()
            ->joinWithSpyPaymentProvider();
        $paymentMethodEntities = $this->applyPaymentMethodFilters(
            $paymentMethodQuery,
            $paymentMethodCriteriaTransfer,
        )->find();

        if (!$paymentMethodEntities->count()) {
            return $paymentMethodCollectionTransfer;
        }

        return $this->getFactory()
            ->createPaymentMapper()
            ->mapPaymentMethodEntityCollectionToPaymentMethodCollectionTransfer(
                $paymentMethodEntities,
                $paymentMethodCollectionTransfer,
            );
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderCriteriaTransfer $paymentProviderCriteriaTransfer
     *
     * @return bool
     */
    public function hasPaymentProvider(PaymentProviderCriteriaTransfer $paymentProviderCriteriaTransfer): bool
    {
        return $this->applyPaymentProviderFilters(
            $this->getFactory()->createPaymentProviderQuery(),
            $paymentProviderCriteriaTransfer,
        )->exists();
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodCriteriaTransfer $paymentMethodCriteriaTransfer
     *
     * @return bool
     */
    public function hasPaymentMethod(PaymentMethodCriteriaTransfer $paymentMethodCriteriaTransfer): bool
    {
        return $this->applyPaymentMethodFilters(
            $this->getFactory()->createPaymentMethodQuery(),
            $paymentMethodCriteriaTransfer,
        )->exists();
    }

    /**
     * @param \Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery $paymentMethodQuery
     * @param \Generated\Shared\Transfer\PaymentMethodCriteriaTransfer $paymentMethodCriteriaTransfer
     *
     * @return \Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery
     */
    protected function applyPaymentMethodFilters(
        SpyPaymentMethodQuery $paymentMethodQuery,
        PaymentMethodCriteriaTransfer $paymentMethodCriteriaTransfer
    ): SpyPaymentMethodQuery {
        $paymentMethodConditionsTransfer = $paymentMethodCriteriaTransfer->getPaymentMethodConditions();

        if (!$paymentMethodConditionsTransfer) {
            return $paymentMethodQuery;
        }

        if ($paymentMethodConditionsTransfer->getNames()) {
            $paymentMethodQuery->filterByName_In($paymentMethodConditionsTransfer->getNames());
        }

        if ($paymentMethodConditionsTransfer->getPaymentMethodIds()) {
            $paymentMethodQuery->filterByIdPaymentMethod_In($paymentMethodConditionsTransfer->getPaymentMethodIds());
        }

        if ($paymentMethodConditionsTransfer->getPaymentMethodKeys()) {
            $paymentMethodQuery->filterByPaymentMethodKey_In($paymentMethodConditionsTransfer->getPaymentMethodKeys());
        }

        return $paymentMethodQuery;
    }

    /**
     * @param \Orm\Zed\Payment\Persistence\SpyPaymentProviderQuery $paymentProviderQuery
     * @param \Generated\Shared\Transfer\PaymentProviderCriteriaTransfer $paymentProviderCriteriaTransfer
     *
     * @return \Orm\Zed\Payment\Persistence\SpyPaymentProviderQuery
     */
    protected function applyPaymentProviderFilters(
        SpyPaymentProviderQuery $paymentProviderQuery,
        PaymentProviderCriteriaTransfer $paymentProviderCriteriaTransfer
    ): SpyPaymentProviderQuery {
        $paymentProviderConditionsTransfer = $paymentProviderCriteriaTransfer->getPaymentProviderConditions();

        if (!$paymentProviderConditionsTransfer) {
            return $paymentProviderQuery;
        }

        if ($paymentProviderConditionsTransfer->getNames()) {
            $paymentProviderQuery->filterByName_In($paymentProviderConditionsTransfer->getNames());
        }

        if ($paymentProviderConditionsTransfer->getPaymentProviderIds()) {
            $paymentProviderQuery->filterByIdPaymentProvider_In($paymentProviderConditionsTransfer->getPaymentProviderIds());
        }

        if ($paymentProviderConditionsTransfer->getPaymentProviderKeys()) {
            $paymentProviderQuery->filterByPaymentProviderKey_In($paymentProviderConditionsTransfer->getPaymentProviderKeys());
        }

        return $paymentProviderQuery;
    }
}
