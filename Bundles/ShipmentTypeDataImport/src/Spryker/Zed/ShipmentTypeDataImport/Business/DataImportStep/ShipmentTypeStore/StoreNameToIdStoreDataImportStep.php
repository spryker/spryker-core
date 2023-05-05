<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShipmentTypeDataImport\Business\DataImportStep\ShipmentTypeStore;

use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ShipmentTypeDataImport\Business\DataSet\ShipmentTypeStoreDataSetInterface;

class StoreNameToIdStoreDataImportStep implements DataImportStepInterface
{
    /**
     * @var array<string, int>
     */
    protected array $storeIdsIndexedByName = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        /** @var string $storeName */
        $storeName = $dataSet[ShipmentTypeStoreDataSetInterface::COLUMN_STORE_NAME];
        if (!isset($this->storeIdsIndexedByName[$storeName])) {
            $this->storeIdsIndexedByName[$storeName] = $this->getIdStoreByName($storeName);
        }

        $dataSet[ShipmentTypeStoreDataSetInterface::ID_STORE] = $this->storeIdsIndexedByName[$storeName];
    }

    /**
     * @param string $storeName
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdStoreByName(string $storeName): int
    {
        /** @var int $idStore */
        $idStore = $this->getStoreQuery()
            ->select(SpyStoreTableMap::COL_ID_STORE)
            ->findOneByName($storeName);

        if (!$idStore) {
            throw new EntityNotFoundException(
                sprintf('Could not find Store by name "%s"', $storeName),
            );
        }

        return $idStore;
    }

    /**
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery
     */
    protected function getStoreQuery(): SpyStoreQuery
    {
        return SpyStoreQuery::create();
    }
}
