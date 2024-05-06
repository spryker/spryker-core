<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Persistence;

use Generated\Shared\Transfer\MerchantCommissionAmountTransfer;
use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommission;
use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionAmount;
use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionMerchant;
use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionStore;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionPersistenceFactory getFactory()
 */
class MerchantCommissionEntityManager extends AbstractEntityManager implements MerchantCommissionEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionTransfer
     */
    public function createMerchantCommission(
        MerchantCommissionTransfer $merchantCommissionTransfer
    ): MerchantCommissionTransfer {
        $merchantCommissionMapper = $this->getFactory()->createMerchantCommissionMapper();
        $merchantCommissionEntity = $merchantCommissionMapper->mapMerchantCommissionTransferToMerchantCommissionEntity(
            $merchantCommissionTransfer,
            new SpyMerchantCommission(),
        );

        $merchantCommissionEntity->save();

        return $merchantCommissionMapper->mapMerchantCommissionEntityToMerchantCommissionTransfer(
            $merchantCommissionEntity,
            $merchantCommissionTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionAmountTransfer $merchantCommissionAmountTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionAmountTransfer
     */
    public function createMerchantCommissionAmount(
        MerchantCommissionAmountTransfer $merchantCommissionAmountTransfer
    ): MerchantCommissionAmountTransfer {
        $merchantCommissionMapper = $this->getFactory()->createMerchantCommissionMapper();
        $merchantCommissionAmountEntity = $merchantCommissionMapper->mapMerchantCommissionAmountTransferToMerchantCommissionAmountEntity(
            $merchantCommissionAmountTransfer,
            new SpyMerchantCommissionAmount(),
        );

        $merchantCommissionAmountEntity->save();

        return $merchantCommissionMapper->mapMerchantCommissionAmountEntityToMerchantCommissionAmountTransfer(
            $merchantCommissionAmountEntity,
            $merchantCommissionAmountTransfer,
        );
    }

    /**
     * @param int $idMerchantCommission
     * @param list<int> $storeIds
     *
     * @return void
     */
    public function createMerchantCommissionStores(int $idMerchantCommission, array $storeIds): void
    {
        foreach ($storeIds as $idStore) {
            (new SpyMerchantCommissionStore())
                ->setFkMerchantCommission($idMerchantCommission)
                ->setFkStore($idStore)
                ->save();
        }
    }

    /**
     * @param int $idMerchantCommission
     * @param list<int> $merchantIds
     *
     * @return void
     */
    public function createMerchantCommissionMerchants(int $idMerchantCommission, array $merchantIds): void
    {
        foreach ($merchantIds as $idMerchant) {
            (new SpyMerchantCommissionMerchant())
                ->setFkMerchantCommission($idMerchantCommission)
                ->setFkMerchant($idMerchant)
                ->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionTransfer
     */
    public function updateMerchantCommission(MerchantCommissionTransfer $merchantCommissionTransfer): MerchantCommissionTransfer
    {
        $merchantCommissionEntity = $this->getFactory()
            ->getMerchantCommissionQuery()
            ->filterByUuid($merchantCommissionTransfer->getUuidOrFail())
            ->findOne();

        if ($merchantCommissionEntity === null) {
            return $merchantCommissionTransfer;
        }

        $merchantCommissionMapper = $this->getFactory()->createMerchantCommissionMapper();
        $merchantCommissionEntity = $merchantCommissionMapper->mapMerchantCommissionTransferToMerchantCommissionEntity(
            $merchantCommissionTransfer,
            $merchantCommissionEntity,
        );
        $merchantCommissionEntity->save();

        return $merchantCommissionMapper->mapMerchantCommissionEntityToMerchantCommissionTransfer(
            $merchantCommissionEntity,
            $merchantCommissionTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionAmountTransfer $merchantCommissionAmountTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionAmountTransfer
     */
    public function updateMerchantCommissionAmount(
        MerchantCommissionAmountTransfer $merchantCommissionAmountTransfer
    ): MerchantCommissionAmountTransfer {
        $merchantCommissionAmountEntity = $this->getFactory()
            ->getMerchantCommissionAmountQuery()
            ->filterByUuid($merchantCommissionAmountTransfer->getUuidOrFail())
            ->findOne();

        if ($merchantCommissionAmountEntity === null) {
            return $merchantCommissionAmountTransfer;
        }

        $merchantCommissionMapper = $this->getFactory()->createMerchantCommissionMapper();
        $merchantCommissionAmountEntity = $merchantCommissionMapper->mapMerchantCommissionAmountTransferToMerchantCommissionAmountEntity(
            $merchantCommissionAmountTransfer,
            $merchantCommissionAmountEntity,
        );
        $merchantCommissionAmountEntity->save();

        return $merchantCommissionMapper->mapMerchantCommissionAmountEntityToMerchantCommissionAmountTransfer(
            $merchantCommissionAmountEntity,
            $merchantCommissionAmountTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionAmountTransfer $merchantCommissionAmountTransfer
     *
     * @return void
     */
    public function deleteMerchantCommissionAmount(MerchantCommissionAmountTransfer $merchantCommissionAmountTransfer): void
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $merchantCommissionAmountEntity */
        $merchantCommissionAmountEntity = $this->getFactory()
            ->getMerchantCommissionAmountQuery()
            ->filterByUuid($merchantCommissionAmountTransfer->getUuidOrFail())
            ->find();

        $merchantCommissionAmountEntity->delete();
    }

    /**
     * @param int $idMerchantCommission
     * @param list<int> $storeIds
     *
     * @return void
     */
    public function deleteMerchantCommissionStores(int $idMerchantCommission, array $storeIds): void
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $merchantComissionStoreEntity */
        $merchantComissionStoreEntity = $this->getFactory()
            ->getMerchantCommissionStoreQuery()
            ->filterByFkMerchantCommission($idMerchantCommission)
            ->filterByFkStore_In($storeIds)
            ->find();

        $merchantComissionStoreEntity->delete();
    }

    /**
     * @param int $idMerchantCommission
     * @param list<int> $merchantIds
     *
     * @return void
     */
    public function deleteMerchantCommissionMerchants(int $idMerchantCommission, array $merchantIds): void
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $merchantCommissionMerchantEntity */
        $merchantCommissionMerchantEntity = $this->getFactory()
            ->getMerchantCommissionMerchantQuery()
            ->filterByFkMerchantCommission($idMerchantCommission)
            ->filterByFkMerchant_In($merchantIds)
            ->find();

        $merchantCommissionMerchantEntity->delete();
    }
}
