<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CategoryDataImport\Business\Writer\CategoryStore;

use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\CategoryDataImport\Business\Writer\CategoryStore\DataSet\CategoryStoreDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class StoreNameToIdStoreStep implements DataImportStepInterface
{
    protected const ALL_STORES_IDENTIFIER = '*';

    /**
     * @var int[]
     */
    protected static $idStoreCache = [];

    /**
     * @var bool
     */
    protected static $areAllStoresLoaded = false;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $dataSet[CategoryStoreDataSetInterface::INCLUDED_STORE_IDS] = $this->getStoreIds(
            $dataSet[CategoryStoreDataSetInterface::COLUMN_INCLUDED_STORE_NAME]
        );
        $dataSet[CategoryStoreDataSetInterface::EXCLUDED_STORE_IDS] = $this->getStoreIds(
            $dataSet[CategoryStoreDataSetInterface::COLUMN_EXCLUDED_STORE_NAME]
        );
    }

    /**
     * @param string $storeNames
     *
     * @return int[]
     */
    protected function getStoreIds(string $storeNames): array
    {
        if (!$storeNames) {
            return [];
        }

        if ($storeNames === static::ALL_STORES_IDENTIFIER) {
            return $this->getAllStoreIds();
        }

        $storeNamesExploded = explode(',', $storeNames);
        $storeIds = [];
        foreach ($storeNamesExploded as $storeName) {
            $storeIds[] = $this->getIdStoreByStoreName($storeName);
        }

        return $storeIds;
    }

    /**
     * @param string $storeName
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdStoreByStoreName(string $storeName): int
    {
        if (isset(static::$idStoreCache[$storeName])) {
            return static::$idStoreCache[$storeName];
        }

        $storeEntity = SpyStoreQuery::create()
            ->filterByName($storeName)
            ->findOne();

        if ($storeEntity === null) {
            throw new EntityNotFoundException(sprintf('Store not found: %s', $storeName));
        }

        static::$idStoreCache[$storeName] = $storeEntity->getIdStore();

        return static::$idStoreCache[$storeName];
    }

    /**
     * @return int[]
     */
    protected function getAllStoreIds(): array
    {
        if (static::$areAllStoresLoaded) {
            return static::$idStoreCache;
        }

        $storeEntities = SpyStoreQuery::create()->find();
        foreach ($storeEntities as $storeEntity) {
            static::$idStoreCache[$storeEntity->getName()] = $storeEntity->getIdStore();
        }

        return static::$idStoreCache;
    }
}
