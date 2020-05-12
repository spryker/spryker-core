<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantDataImport\Business\MerchantStore\Step;

use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantDataImport\Business\MerchantStore\DataSet\MerchantStoreDataSetInterface;

class StoreNameToIdStoreStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $idStoreCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $storeName = $dataSet[MerchantStoreDataSetInterface::STORE_NAME];

        if (!isset($this->idStoreCache[$storeName])) {
            $this->idStoreCache[$storeName] = $this->getIdStore($storeName);
        }

        $dataSet[MerchantStoreDataSetInterface::ID_STORE] = $this->idStoreCache[$storeName];
    }

    /**
     * @module Store
     *
     * @param string $storeName
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdStore(string $storeName): int
    {
        $storeQuery = SpyStoreQuery::create();
        $storeQuery->select(SpyStoreTableMap::COL_ID_STORE);

        /** @var int $idStore */
        $idStore = $storeQuery->findOneByName($storeName);

        if (!$idStore) {
            throw new EntityNotFoundException(sprintf('Could not find store by name "%s"', $storeName));
        }

        return $idStore;
    }
}
