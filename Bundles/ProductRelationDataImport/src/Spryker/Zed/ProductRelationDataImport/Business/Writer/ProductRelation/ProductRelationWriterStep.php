<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationDataImport\Business\Writer\ProductRelation;

use Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductRelationDataImport\Business\Writer\ProductRelation\DataSet\ProductRelationDataSetInterface;
use Spryker\Zed\ProductRelationDataImport\ProductRelationDataImportConfig;

class ProductRelationWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productRelationEntity = SpyProductRelationQuery::create()
            ->filterByProductRelationKey($dataSet[ProductRelationDataSetInterface::COL_PRODUCT_RELATION_KEY])
            ->findOneOrCreate();

        $productRelationEntity
            ->setFkProductAbstract($dataSet[ProductRelationDataSetInterface::COL_ID_PRODUCT_ABSTRACT])
            ->setQuerySetData($dataSet[ProductRelationDataSetInterface::COL_RULE])
            ->setFkProductRelationType($dataSet[ProductRelationDataSetInterface::COL_ID_PRODUCT_RELATION_TYPE])
            ->setIsActive($dataSet[ProductRelationDataSetInterface::COL_IS_ACTIVE] ?? false)
            ->setIsRebuildScheduled($dataSet[ProductRelationDataSetInterface::COL_IS_REBUILD_SCHEDULED] ?? false)
            ->save();

        $this->addPublishEvents(ProductRelationDataImportConfig::PRODUCT_ABSTRACT_RELATION_PUBLISH, $productRelationEntity->getFkProductAbstract());
    }
}
