<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductRelationDataImport\Business\Writer\ProductRelationStore;

use Orm\Zed\ProductRelation\Persistence\SpyProductRelationStoreQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductRelationDataImport\Business\Writer\ProductRelationStore\DataSet\ProductRelationStoreDataSetInterface;
use Spryker\Zed\ProductRelationDataImport\ProductRelationDataImportConfig;

class ProductRelationStoreWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productRelationStoreEntity = SpyProductRelationStoreQuery::create()
            ->filterByFkStore($dataSet[ProductRelationStoreDataSetInterface::COL_ID_STORE])
            ->filterByFkProductRelation($dataSet[ProductRelationStoreDataSetInterface::COL_ID_PRODUCT_RELATION])
            ->findOneOrCreate();

        $productRelationStoreEntity->save();

        $productRelationEntity = $productRelationStoreEntity->getProductRelation();

        $this->addPublishEvents(ProductRelationDataImportConfig::PRODUCT_ABSTRACT_RELATION_STORE_PUBLISH, $productRelationEntity->getFkProductAbstract());
    }
}
