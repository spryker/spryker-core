<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business\Model\Step;

use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business\Model\DataSet\PriceProductMerchantRelationshipDataSetInterface;

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
        $storeName = $dataSet[PriceProductMerchantRelationshipDataSetInterface::STORE];
        if (!isset($this->idStoreCache[$storeName])) {
            $idCurrency = SpyStoreQuery::create()
                ->select(SpyStoreTableMap::COL_ID_STORE)
                ->findOneByName($storeName);

            if (!$idCurrency) {
                throw new EntityNotFoundException(sprintf('Could not find store by code "%s"', $storeName));
            }

            $this->idStoreCache[$storeName] = $idCurrency;
        }

        $dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_STORE] = $this->idStoreCache[$storeName];
    }
}
