<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business\Model\Step;

use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationshipQuery;
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
        $priceProductStoreEntity = $this->getPriceProductStoreEntityForMerchantRelationship($dataSet);

        $dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_PRICE_PRODUCT_STORE] = $priceProductStoreEntity->getIdPriceProductStore();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore
     */
    protected function getPriceProductStoreEntityForMerchantRelationship(
        DataSetInterface $dataSet
    ): SpyPriceProductStore {
        $priceProductStoreEntity = $this->findExistingPriceProductStoreEntity($dataSet);

        if (!$priceProductStoreEntity) {
            return $this->getPriceProductStoreEntity($dataSet);
        }

        $netPrice = (int)$dataSet[PriceProductMerchantRelationshipDataSetInterface::PRICE_NET];
        $grossPrice = (int)$dataSet[PriceProductMerchantRelationshipDataSetInterface::PRICE_GROSS];
        if ($priceProductStoreEntity->getGrossPrice() === $grossPrice && $priceProductStoreEntity->getNetPrice() === $netPrice) {
            return $priceProductStoreEntity;
        }

        $this->removeNotActualRelation(
            $dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_MERCHANT_RELATIONSHIP],
            $priceProductStoreEntity->getIdPriceProductStore()
        );

        return $this->getPriceProductStoreEntity($dataSet);
    }

    /**
     * Removes outdated record from spy_price_product_merchant_relationship.
     *
     * @param int $idMerchantRelationship
     * @param string $idPriceProductStoreEntity
     *
     * @return void
     */
    protected function removeNotActualRelation(int $idMerchantRelationship, string $idPriceProductStoreEntity): void
    {
        $priceProductMerchantRelationshipEntity = SpyPriceProductMerchantRelationshipQuery::create()
            ->filterByFkMerchantRelationship($idMerchantRelationship)
            ->filterByFkPriceProductStore($idPriceProductStoreEntity)
            ->findOne();

        if ($priceProductMerchantRelationshipEntity) {
            $priceProductMerchantRelationshipEntity->delete();
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore|null
     */
    protected function findExistingPriceProductStoreEntity(DataSetInterface $dataSet): ?SpyPriceProductStore
    {
        return SpyPriceProductStoreQuery::create()
            ->usePriceProductMerchantRelationshipQuery()
                ->filterByFkMerchantRelationship($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_MERCHANT_RELATIONSHIP])
            ->endUse()
            ->filterByFkStore($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_STORE])
            ->filterByFkCurrency($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_CURRENCY])
            ->filterByFkPriceProduct($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_PRICE_PRODUCT])
            ->findOne();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore
     */
    protected function getPriceProductStoreEntity(DataSetInterface $dataSet): SpyPriceProductStore
    {
        $netPrice = (int)$dataSet[PriceProductMerchantRelationshipDataSetInterface::PRICE_NET];
        $grossPrice = (int)$dataSet[PriceProductMerchantRelationshipDataSetInterface::PRICE_GROSS];

        $priceProductStoreEntity = SpyPriceProductStoreQuery::create()
            ->filterByFkStore($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_STORE])
            ->filterByFkCurrency($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_CURRENCY])
            ->filterByFkPriceProduct($dataSet[PriceProductMerchantRelationshipDataSetInterface::ID_PRICE_PRODUCT])
            ->filterByNetPrice($netPrice)
            ->filterByGrossPrice($grossPrice)
            ->findOneOrCreate();

        $priceProductStoreEntity->save();

        return $priceProductStoreEntity;
    }
}
