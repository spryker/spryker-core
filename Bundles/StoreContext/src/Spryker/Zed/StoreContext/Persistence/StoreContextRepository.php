<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Persistence;

use Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\StoreContext\Persistence\StoreContextPersistenceFactory getFactory()
 */
class StoreContextRepository extends AbstractRepository implements StoreContextRepositoryInterface
{
    /**
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer|null
     */
    public function findStoreApplicationContextCollectionByIdStore(int $idStore): ?StoreApplicationContextCollectionTransfer
    {
        $storeContextEntity = $this->getFactory()
            ->createStoreContextQuery()
            ->filterByFkStore($idStore)
            ->findOne();

        if (!$storeContextEntity) {
            return null;
        }

        return $this->getFactory()
            ->createStoreContextMapper()
            ->mapStoreContextEntityToStoreApplicationContextCollectionTransfer(
                $storeContextEntity,
                new StoreApplicationContextCollectionTransfer(),
            );
    }

    /**
     * Result format:
     * [
     *     $idStore => StoreContextCollectionTransfer,
     *     ...
     * ]
     *
     * @param array<int> $storeIds
     *
     * @return array<int, \Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer>
     */
    public function getStoreApplicationContextCollectionsIndexedByIdStore(array $storeIds): array
    {
        $storeContextEntityCollection = $this->getFactory()
            ->createStoreContextQuery()
            ->filterByFkStore_In($storeIds)
            ->find();

        if ($storeContextEntityCollection->count() === 0) {
            return [];
        }

        return $this->indexStoreApplicationContextCollectionTransfer($storeContextEntityCollection->getData());
    }

    /**
     * @param array<\Orm\Zed\StoreContext\Persistence\SpyStoreContext> $storeContextEntities
     *
     * @return array<\Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer>
     */
    protected function indexStoreApplicationContextCollectionTransfer(array $storeContextEntities): array
    {
        $storeApplicationContextCollectionTransfers = [];

        foreach ($storeContextEntities as $storeContextEntity) {
            $storeContextCollectionTransfer = $this->getFactory()
                ->createStoreContextMapper()
                ->mapStoreContextEntityToStoreApplicationContextCollectionTransfer(
                    $storeContextEntity,
                    new StoreApplicationContextCollectionTransfer(),
                );

            $storeApplicationContextCollectionTransfers[$storeContextEntity->getFkStore()] = $storeContextCollectionTransfer;
        }

        return $storeApplicationContextCollectionTransfers;
    }
}
