<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationDataImport\Business\Writer\ProductRelation;

use Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductRelationDataImport\Business\Exception\DuplicatedStoresException;
use Spryker\Zed\ProductRelationDataImport\Business\Writer\ProductRelation\DataSet\ProductRelationDataSetInterface;

class ProductAndRelationTypeAndStoreValidatorStep implements DataImportStepInterface
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
        $currentProductRelationEntityStores = SpyProductRelationQuery::create()
            ->filterByProductRelationKey($dataSet[ProductRelationDataSetInterface::COL_PRODUCT_RELATION_KEY])
            ->leftJoinWithProductRelationStore()
            ->useProductRelationStoreQuery()
                ->leftJoinWithStore()
            ->endUse()
            ->select([
                SpyStoreTableMap::COL_NAME,
            ])
            ->find()
            ->getData();

        $storesForFkProductAbstractAndRelationKey = SpyProductRelationQuery::create()
            ->filterByProductRelationKey($dataSet[ProductRelationDataSetInterface::COL_PRODUCT_RELATION_KEY], Criteria::NOT_EQUAL)
            ->filterByFkProductAbstract($dataSet[ProductRelationDataSetInterface::COL_ID_PRODUCT_ABSTRACT])
            ->filterByFkProductRelationType($dataSet[ProductRelationDataSetInterface::COL_ID_PRODUCT_RELATION_TYPE])
            ->leftJoinProductRelationStore()
            ->useProductRelationStoreQuery()
                ->leftJoinWithStore()
            ->endUse()
            ->select([
                SpyStoreTableMap::COL_NAME,
            ])
            ->distinct()
            ->find()
            ->getData();

        if (array_intersect($currentProductRelationEntityStores, $storesForFkProductAbstractAndRelationKey) !== []) {
            throw new DuplicatedStoresException('Impossible assign product relation to the given store');
        }
    }
}
