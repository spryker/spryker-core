<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ServicePointCollectionTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ServicePoint\Persistence\SpyServicePoint;
use Propel\Runtime\Collection\ObjectCollection;

class ServicePointMapper
{
    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     * @param \Orm\Zed\ServicePoint\Persistence\SpyServicePoint $servicePointEntity
     *
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServicePoint
     */
    public function mapServicePointTransferToServicePointEntity(
        ServicePointTransfer $servicePointTransfer,
        SpyServicePoint $servicePointEntity
    ): SpyServicePoint {
        return $servicePointEntity->fromArray($servicePointTransfer->modifiedToArray());
    }

    /**
     * @param \Orm\Zed\ServicePoint\Persistence\SpyServicePoint $servicePointEntity
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    public function mapServicePointEntityToServicePointTransfer(
        SpyServicePoint $servicePointEntity,
        ServicePointTransfer $servicePointTransfer
    ): ServicePointTransfer {
        return $servicePointTransfer->fromArray(
            $servicePointEntity->toArray(),
            true,
        );
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ServicePoint\Persistence\SpyServicePoint> $servicePointEntities
     * @param \Generated\Shared\Transfer\ServicePointCollectionTransfer $servicePointCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointCollectionTransfer
     */
    public function mapServicePointEntitiesToServicePointCollectionTransfer(
        ObjectCollection $servicePointEntities,
        ServicePointCollectionTransfer $servicePointCollectionTransfer
    ): ServicePointCollectionTransfer {
        foreach ($servicePointEntities as $servicePointEntity) {
            $servicePointCollectionTransfer->addServicePoint(
                $this->mapServicePointEntityToServicePointTransfer($servicePointEntity, new ServicePointTransfer()),
            );
        }

        return $servicePointCollectionTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ServicePoint\Persistence\SpyServicePointStore> $servicePointStoreEntities
     *
     * @return array<int, list<\Generated\Shared\Transfer\StoreTransfer>>
     */
    public function mapServicePointStoreEntitiesToStoreTransfers(
        ObjectCollection $servicePointStoreEntities
    ): array {
        $storeTransfersGroupedByIdServicePoint = [];

        foreach ($servicePointStoreEntities as $servicePointStoreEntity) {
            $storeTransfersGroupedByIdServicePoint[$servicePointStoreEntity->getFkServicePoint()][] =
                (new StoreTransfer())->fromArray(
                    $servicePointStoreEntity->getStore()->toArray(),
                    true,
                );
        }

        return $storeTransfersGroupedByIdServicePoint;
    }
}
