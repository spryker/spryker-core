<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Persistence;

use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Store\Persistence\StorePersistenceFactory getFactory()
 */
class StoreRepository extends AbstractRepository implements StoreRepositoryInterface
{
    /**
     * @param string $name
     *
     * @return bool
     */
    public function storeExists(string $name): bool
    {
        return $this->getFactory()
            ->createStoreQuery()
            ->filterByName($name)
            ->exists();
    }

    /**
     * @param string[] $storeNames
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getStoreTransfersByStoreNames(array $storeNames): array
    {
        $storeEntities = $this->getFactory()
            ->createStoreQuery()
            ->filterByName_In($storeNames)
            ->find();

        if ($storeEntities->count() === 0) {
            return [];
        }

        return $this->mapStoreEntitiesToStoreTransfers($storeEntities);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Store\Persistence\SpyStore[] $storeEntities
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    protected function mapStoreEntitiesToStoreTransfers(ObjectCollection $storeEntities): array
    {
        $mapper = $this->getFactory()->createStoreMapper();
        $storeTransfers = [];
        foreach ($storeEntities as $storeEntity) {
            $storeTransfers[] = $mapper->mapStoreEntityToStoreTransfer($storeEntity);
        }

        return $storeTransfers;
    }
}
