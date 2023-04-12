<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ServicePoint\Helper;

use ArrayObject;
use Codeception\Module;
use Generated\Shared\DataBuilder\ServicePointBuilder;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ServicePoint\Persistence\SpyServicePoint;
use Orm\Zed\ServicePoint\Persistence\SpyServicePointQuery;
use Orm\Zed\ServicePoint\Persistence\SpyServicePointStore;
use Orm\Zed\ServicePoint\Persistence\SpyServicePointStoreQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class ServicePointHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param array<string, mixed> $seed
     *
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    public function haveServicePoint(array $seed = []): ServicePointTransfer
    {
        $servicePointTransfer = (new ServicePointBuilder($seed))->build();

        $servicePointEntity = (new SpyServicePoint())
            ->fromArray($servicePointTransfer->toArray());

        $servicePointEntity->save();
        $servicePointTransfer->fromArray($servicePointEntity->toArray(), true);

        if ($servicePointTransfer->getStoreRelation()) {
            $storeTransfers = [];

            foreach ($servicePointTransfer->getStoreRelationOrFail()->getStores() as $storeTransfer) {
                $storeTransfers[] = $this->createServicePointStore(
                    $servicePointEntity->getIdServicePoint(),
                    $storeTransfer->getIdStoreOrFail(),
                );
            }

            $servicePointTransfer->getStoreRelationOrFail()->setStores(
                new ArrayObject($storeTransfers),
            );
        }

        $this->getDataCleanupHelper()->_addCleanup(function () use ($servicePointEntity): void {
            $this->deleteServicePoint($servicePointEntity->getIdServicePoint());
        });

        return $servicePointTransfer;
    }

    /**
     * @param int $idServicePoint
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function createServicePointStore(
        int $idServicePoint,
        int $idStore
    ): StoreTransfer {
        $servicePointStoreEntity = (new SpyServicePointStore())
            ->setFkServicePoint($idServicePoint)
            ->setFkStore($idStore);

        $servicePointStoreEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($servicePointStoreEntity): void {
            $this->deleteServicePointStore($servicePointStoreEntity->getIdServicePointStore());
        });

        $storeEntity = $servicePointStoreEntity->getStore();

        return (new StoreTransfer())->fromArray(
            $storeEntity->toArray(),
            true,
        );
    }

    /**
     * @param int $idServicePoint
     *
     * @return void
     */
    protected function deleteServicePoint(int $idServicePoint): void
    {
        $servicePointEntity = $this->getServicePointQuery()->findOneByIdServicePoint($idServicePoint);

        if ($servicePointEntity) {
            $servicePointEntity->delete();
        }
    }

    /**
     * @param int $idServicePointStore
     *
     * @return void
     */
    protected function deleteServicePointStore(int $idServicePointStore): void
    {
        $servicePointStoreEntity = $this->getServicePointStoreQuery()->findOneByIdServicePointStore($idServicePointStore);

        if ($servicePointStoreEntity) {
            $servicePointStoreEntity->delete();
        }
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServicePointQuery
     */
    protected function getServicePointQuery(): SpyServicePointQuery
    {
        return SpyServicePointQuery::create();
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServicePointStoreQuery
     */
    protected function getServicePointStoreQuery(): SpyServicePointStoreQuery
    {
        return SpyServicePointStoreQuery::create();
    }
}
