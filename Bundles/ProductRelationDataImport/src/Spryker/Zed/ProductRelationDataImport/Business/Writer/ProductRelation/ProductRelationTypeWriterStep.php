<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationDataImport\Business\Writer\ProductRelation;

use Orm\Zed\ProductRelation\Persistence\SpyProductRelationTypeQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductRelationDataImport\Business\Writer\ProductRelation\DataSet\ProductRelationDataSetInterface;

class ProductRelationTypeWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productRelationTypeEntity = SpyProductRelationTypeQuery::create()
            ->filterByKey($dataSet[ProductRelationDataSetInterface::COL_RELATION_TYPE])
            ->findOneOrCreate();

        $productRelationTypeEntity->save();

        $dataSet[ProductRelationDataSetInterface::COL_ID_PRODUCT_RELATION_TYPE] = $productRelationTypeEntity->getIdProductRelationType();
    }
}
