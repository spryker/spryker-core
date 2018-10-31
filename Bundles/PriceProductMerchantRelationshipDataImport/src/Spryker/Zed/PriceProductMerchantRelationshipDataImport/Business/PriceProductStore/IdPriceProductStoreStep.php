<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business\PriceProductStore;

use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business\Model\DataSet\PriceProductMerchantRelationshipDataSetInterface;

class IdPriceProductStoreStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $priceProductStoreEntity = $this->getPriceProductStoreEntity($dataSet);

        $dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_PRICE_PRODUCT_STORE] = $priceProductStoreEntity->getIdPriceProductStore();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore
     */
    protected function getPriceProductStoreEntity(DataSetInterface $dataSet): SpyPriceProductStore
    {
        $priceProductStoreEntity = SpyPriceProductStoreQuery::create()
            ->filterByFkStore($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_STORE])
            ->filterByFkCurrency($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_CURRENCY])
            ->filterByFkPriceProduct($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_PRICE_PRODUCT])
            ->filterByNetPrice((int)$dataSet[PriceProductMerchantRelationshipDataSetInterface::PRICE_NET])
            ->filterByGrossPrice((int)$dataSet[PriceProductMerchantRelationshipDataSetInterface::PRICE_GROSS])
            ->findOneOrCreate();

        $priceProductStoreEntity->save();

        return $priceProductStoreEntity;
    }
}
