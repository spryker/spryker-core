<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationDataImport\Business\Writer\ProductRelationStore;

use Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationStoreQuery;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductRelationDataImport\Business\Exception\DuplicatedStoresException;
use Spryker\Zed\ProductRelationDataImport\Business\Writer\ProductRelationStore\DataSet\ProductRelationStoreDataSetInterface;

class StoreValidatorStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\ProductRelationDataImport\Business\Exception\DuplicatedStoresException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productRelationKey = $dataSet[ProductRelationStoreDataSetInterface::COL_PRODUCT_RELATION_KEY];
        $productRelationEntity = SpyProductRelationQuery::create()
            ->filterByProductRelationKey($productRelationKey)
            ->findOne();

        $storeName = $dataSet[ProductRelationStoreDataSetInterface::COL_STORE_NAME];

        $storesData = SpyProductRelationStoreQuery::create()
            ->leftJoinWithStore()
            ->select([
                SpyStoreTableMap::COL_NAME,
            ])
            ->distinct()
            ->useProductRelationQuery()
                ->filterByFkProductAbstract($productRelationEntity->getFkProductAbstract())
                ->filterByFkProductRelationType($productRelationEntity->getFkProductRelationType())
                ->filterByProductRelationKey($productRelationKey, Criteria::NOT_EQUAL)
            ->endUse()
            ->find()
            ->getData();

        if (in_array($storeName, $storesData)) {
            throw new DuplicatedStoresException('Impossible assign product relation to the given store');
        }
    }
}
