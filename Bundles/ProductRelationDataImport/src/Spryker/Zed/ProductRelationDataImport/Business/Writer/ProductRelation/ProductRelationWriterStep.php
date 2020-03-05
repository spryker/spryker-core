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
use Spryker\Zed\ProductRelation\Dependency\ProductRelationEvents;
use Spryker\Zed\ProductRelationDataImport\Business\Writer\ProductRelation\DataSet\ProductRelationDataSetInterface;

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
            ->filterByFkProductAbstract($dataSet[ProductRelationDataSetInterface::COL_ID_PRODUCT_ABSTRACT])
            ->filterByFkProductRelationType($dataSet[ProductRelationDataSetInterface::COL_ID_PRODUCT_RELATION_TYPE])
            ->findOneOrCreate();

        $productRelationEntity
            ->setQuerySetData($dataSet[ProductRelationDataSetInterface::COL_RULE])
            ->setProductRelationKey($dataSet[ProductRelationDataSetInterface::COL_PRODUCT_RELATION_KEY])
            ->setIsActive($dataSet[ProductRelationDataSetInterface::COL_IS_ACTIVE] ?? false)
            ->setIsRebuildScheduled($dataSet[ProductRelationDataSetInterface::COL_IS_REBUILD_SCHEDULED] ?? false)
            ->save();

        $this->addPublishEvents(ProductRelationEvents::ENTITY_SPY_PRODUCT_RELATION_STORE_PUBLISH, $productRelationEntity->getFkProductAbstract());
    }
}
