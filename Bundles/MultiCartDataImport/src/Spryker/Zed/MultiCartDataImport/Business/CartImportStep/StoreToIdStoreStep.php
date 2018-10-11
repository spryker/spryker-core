<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MultiCartDataImport\Business\CartImportStep;

use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MultiCartDataImport\Business\DataSet\CartDataSetInterface;

class StoreToIdStoreStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $idStoreCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $storeName = $dataSet[CartDataSetInterface::KEY_STORE];
        if (!isset($this->idStoreCache[$storeName])) {
            $storeQuery = new SpyStoreQuery();
            $idStore = $storeQuery
                ->select(SpyStoreTableMap::COL_ID_STORE)
                ->findOneByName($storeName);

            if (!$idStore) {
                throw new EntityNotFoundException(sprintf('Could not find store by name "%s"', $storeName));
            }

            $this->idStoreCache[$storeName] = $idStore;
        }

        $dataSet[CartDataSetInterface::ID_STORE] = $this->idStoreCache[$storeName];
    }
}
