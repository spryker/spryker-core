<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business\PriceProductStore;

use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business\Model\DataSet\PriceProductMerchantRelationshipDataSetInterface;

class IdPriceProductStoreStep implements DataImportStepInterface
{
    /**
     * @var string[]
     */
    protected $idPriceProductStoreCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_PRICE_PRODUCT_STORE] = $this->getIdPriceProductStoreEntity($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return string
     */
    protected function getIdPriceProductStoreEntity(DataSetInterface $dataSet): string
    {
        $cacheIndex = $this->buildCacheIndex($dataSet);
        if (isset($this->idPriceProductStoreCache[$cacheIndex])) {
            return $this->idPriceProductStoreCache[$cacheIndex];
        }

        $priceProductStoreEntity = SpyPriceProductStoreQuery::create()
            ->filterByFkStore($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_STORE])
            ->filterByFkCurrency($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_CURRENCY])
            ->filterByFkPriceProduct($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_PRICE_PRODUCT])
            ->filterByNetPrice((int)$dataSet[PriceProductMerchantRelationshipDataSetInterface::PRICE_NET])
            ->filterByGrossPrice((int)$dataSet[PriceProductMerchantRelationshipDataSetInterface::PRICE_GROSS])
            ->findOneOrCreate();

        $priceProductStoreEntity->save();

        $this->idPriceProductStoreCache[$cacheIndex] = $priceProductStoreEntity->getIdPriceProductStore();

        return $this->idPriceProductStoreCache[$cacheIndex];
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return string
     */
    protected function buildCacheIndex(DataSetInterface $dataSet): string
    {
        return implode('-', [
            $dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_STORE],
            $dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_CURRENCY],
            $dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_PRICE_PRODUCT],
            $dataSet[PriceProductMerchantRelationshipDataSetInterface::PRICE_NET],
            $dataSet[PriceProductMerchantRelationshipDataSetInterface::PRICE_GROSS],
        ]);
    }
}
