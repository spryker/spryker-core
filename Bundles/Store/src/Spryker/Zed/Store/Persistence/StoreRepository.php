<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Persistence;

use Generated\Shared\Transfer\StoreCriteriaTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Propel\Runtime\Collection\Collection;
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
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\StoreTransfer|null
     */
    public function findStoreByName(string $storeName): ?StoreTransfer
    {
        $storeEntity = $this->getFactory()
            ->createStoreQuery()
            ->findOneByName($storeName);

        if (!$storeEntity) {
            return null;
        }

        return $this->getFactory()
            ->createStoreMapper()
            ->mapStoreEntityToStoreTransfer($storeEntity, new StoreTransfer());
    }

    /**
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\StoreTransfer|null
     */
    public function findStoreById(int $idStore): ?StoreTransfer
    {
        $storeEntity = $this->getFactory()
            ->createStoreQuery()
            ->findOneByIdStore($idStore);

        if (!$storeEntity) {
            return null;
        }

        return $this->getFactory()
            ->createStoreMapper()
            ->mapStoreEntityToStoreTransfer($storeEntity, new StoreTransfer());
    }

    /**
     * @return array<string>
     */
    public function getStoreNames(): array
    {
        return $this->getFactory()
            ->createStoreQuery()
            ->select(SpyStoreTableMap::COL_NAME)
            ->find()
            ->getData();
    }

    /**
     * @param array<string> $storeNames
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
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
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\Store\Persistence\SpyStore> $storeEntities
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    protected function mapStoreEntitiesToStoreTransfers(Collection $storeEntities): array
    {
        $mapper = $this->getFactory()->createStoreMapper();
        $storeTransfers = [];
        foreach ($storeEntities as $storeEntity) {
            $storeTransfers[] = $mapper->mapStoreEntityToStoreTransfer($storeEntity, new StoreTransfer());
        }

        return $storeTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreCriteriaTransfer $storeCriteriaTransfer
     *
     * @return array<string>
     */
    public function getStoreNamesByCriteria(StoreCriteriaTransfer $storeCriteriaTransfer): array
    {
        $storeQuery = $this->getFactory()
            ->createStoreQuery();
        $storeQuery->select([SpyStoreTableMap::COL_NAME]);

        $storeConditionsTransfer = $storeCriteriaTransfer->getStoreConditions();
        $paginationTransfer = $storeCriteriaTransfer->getPagination();

        if ($storeConditionsTransfer && $storeConditionsTransfer->getStoreIds() !== []) {
            $storeQuery->filterByPrimaryKeys($storeConditionsTransfer->getStoreIds());
        }

        if ($paginationTransfer) {
            $storeQuery->setOffset($paginationTransfer->getOffsetOrFail())
                ->setOffset($paginationTransfer->getOffsetOrFail());
        }

        return $storeQuery->find()->getData();
    }
}
