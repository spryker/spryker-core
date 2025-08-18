<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\SspModel\DataImport\Step;

use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspModelQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspModelToProductListQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\SspModel\DataImport\DataSet\SspModelProductListDataSetInterface;

class SspModelProductListWriterStep implements DataImportStepInterface
{
    public function execute(DataSetInterface $dataSet): void
    {
        $modelReference = $dataSet[SspModelProductListDataSetInterface::COLUMN_MODEL_REFERENCE];
        $productListKey = $dataSet[SspModelProductListDataSetInterface::COLUMN_PRODUCT_LIST_KEY];

        $sspModelEntity = SpySspModelQuery::create()
            ->filterByReference($modelReference)
            ->findOne();

        if (!$sspModelEntity) {
            throw new EntityNotFoundException($modelReference);
        }

        $productListEntity = SpyProductListQuery::create()
            ->filterByKey($productListKey)
            ->findOne();

        if (!$productListEntity) {
            throw new EntityNotFoundException($productListKey);
        }

        $sspModelToProductListEntity = SpySspModelToProductListQuery::create()
            ->filterByFkSspModel($sspModelEntity->getIdSspModel())
            ->filterByFkProductList($productListEntity->getIdProductList())
            ->findOneOrCreate();

        if ($sspModelToProductListEntity->isNew()) {
            $sspModelToProductListEntity->save();
        }
    }
}
