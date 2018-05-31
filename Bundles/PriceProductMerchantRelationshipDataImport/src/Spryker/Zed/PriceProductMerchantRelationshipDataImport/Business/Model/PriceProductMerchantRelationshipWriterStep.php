<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business\Model;

use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProduct;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery;
use Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationshipQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business\Model\DataSet\PriceProductMerchantRelationshipDataSet;

class PriceProductMerchantRelationshipWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productPriceEntity = $this->getProductPriceEntity($dataSet);

        $priceProductStoreEntity = $this->getPriceProductStoreEntityForMerchantRelationship(
            $dataSet,
            $productPriceEntity->getPrimaryKey()
        );

        if ($priceProductStoreEntity->getPriceProductMerchantRelationships()->count() !== 0) {
            return;
        }

        $this->savePriceProductMerchantRelationship($dataSet, $priceProductStoreEntity->getPrimaryKey());
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProduct
     */
    protected function getProductPriceEntity(DataSetInterface $dataSet): SpyPriceProduct
    {
        $priceTypeEntity = SpyPriceTypeQuery::create()
            ->filterByName($dataSet[PriceProductMerchantRelationshipDataSet::PRICE_TYPE])
            ->findOneOrCreate();

        if ($priceTypeEntity->isNew() || $priceTypeEntity->isModified()) {
            $priceTypeEntity->setPriceModeConfiguration(SpyPriceTypeTableMap::COL_PRICE_MODE_CONFIGURATION_BOTH);
            $priceTypeEntity->save();
        }

        $query = SpyPriceProductQuery::create();
        $query->filterByFkPriceType($priceTypeEntity->getIdPriceType());

        if (!empty($dataSet[PriceProductMerchantRelationshipDataSet::ID_PRODUCT_ABSTRACT])) {
            $query->filterByFkProductAbstract($dataSet[PriceProductMerchantRelationshipDataSet::ID_PRODUCT_ABSTRACT]);
        } else {
            $query->filterByFkProduct($dataSet[PriceProductMerchantRelationshipDataSet::ID_PRODUCT_CONCRETE]);
        }
        $productPriceEntity = $query->findOneOrCreate();
        $productPriceEntity->save();

        return $productPriceEntity;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param int $idProductPriceEntity
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore
     */
    protected function getPriceProductStoreEntityForMerchantRelationship(
        DataSetInterface $dataSet,
        int $idProductPriceEntity
    ): SpyPriceProductStore {
        $priceProductStoreEntity = SpyPriceProductStoreQuery::create()
            ->usePriceProductMerchantRelationshipQuery()
                ->filterByFkMerchantRelationship($dataSet[PriceProductMerchantRelationshipDataSet::ID_MERCHANT_RELATIONSHIP])
            ->endUse()
            ->filterByFkStore($dataSet[PriceProductMerchantRelationshipDataSet::ID_STORE])
            ->filterByFkCurrency($dataSet[PriceProductMerchantRelationshipDataSet::ID_CURRENCY])
            ->filterByFkPriceProduct($idProductPriceEntity)
            ->findOne();

        if ($priceProductStoreEntity) {
            return $priceProductStoreEntity;
        }

        $priceProductStoreEntity = SpyPriceProductStoreQuery::create()
            ->filterByFkStore($dataSet[PriceProductMerchantRelationshipDataSet::ID_STORE])
            ->filterByFkCurrency($dataSet[PriceProductMerchantRelationshipDataSet::ID_CURRENCY])
            ->filterByFkPriceProduct($idProductPriceEntity)
            ->findOneOrCreate();

        $priceProductStoreEntity->save();

        return $priceProductStoreEntity;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $idPriceProductStore
     *
     * @return void
     */
    protected function savePriceProductMerchantRelationship(DataSetInterface $dataSet, string $idPriceProductStore): void
    {
        $priceProductMerchantRelationshipQuery = SpyPriceProductMerchantRelationshipQuery::create()
            ->filterByFkMerchantRelationship($dataSet[PriceProductMerchantRelationshipDataSet::ID_MERCHANT_RELATIONSHIP])
            ->filterByFkPriceProductStore($idPriceProductStore);

        if (!empty($dataSet[PriceProductMerchantRelationshipDataSet::ID_PRODUCT_ABSTRACT])) {
            $priceProductMerchantRelationshipQuery->filterByFkProductAbstract($dataSet[PriceProductMerchantRelationshipDataSet::ID_PRODUCT_ABSTRACT]);
        } else {
            $priceProductMerchantRelationshipQuery->filterByFkProduct($dataSet[PriceProductMerchantRelationshipDataSet::ID_PRODUCT_CONCRETE]);
        }

        $priceProductMerchantRelationshipEntity = $priceProductMerchantRelationshipQuery->findOneOrCreate();
        $priceProductMerchantRelationshipEntity->save();
    }
}
