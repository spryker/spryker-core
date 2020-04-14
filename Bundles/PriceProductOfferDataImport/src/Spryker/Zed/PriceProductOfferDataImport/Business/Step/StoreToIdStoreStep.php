<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PriceProductOfferDataImport\Business\Step;

use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductOfferDataImport\Business\DataSet\PriceProductOfferDataSetInterface;

class StoreToIdStoreStep implements DataImportStepInterface
{
    /**
     * @var array
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
        $storeName = $dataSet[PriceProductOfferDataSetInterface::STORE];

        if (!isset($this->idStoreCache[$storeName])) {
            $storeQuery = new SpyStoreQuery();
            $storeQuery->select(SpyStoreTableMap::COL_ID_STORE);
            $idStore = $storeQuery->findOneByName($storeName);

            if (!$idStore) {
                throw new EntityNotFoundException(sprintf('Could not find store by name "%s"', $storeName));
            }

            $this->idStoreCache[$storeName] = $idStore;
        }

        $dataSet[PriceProductOfferDataSetInterface::FK_STORE] = $this->idStoreCache[$storeName];
    }
}
