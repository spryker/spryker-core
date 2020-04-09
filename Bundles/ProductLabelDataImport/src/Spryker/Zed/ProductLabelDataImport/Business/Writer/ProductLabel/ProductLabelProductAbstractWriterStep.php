<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelDataImport\Business\Writer\ProductLabel;

use Orm\Zed\ProductLabel\Persistence\SpyProductLabelProductAbstractQuery;
use Spryker\Shared\ProductLabelStorage\ProductLabelStorageConfig;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\ProductLabelDataImport\Business\Writer\ProductLabel\DataSet\ProductLabelDataSetInterface;

class ProductLabelProductAbstractWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (!isset($dataSet[ProductLabelDataSetInterface::COL_PRODUCT_ABSTRACT_IDS])) {
            return;
        }

        foreach ($dataSet[ProductLabelDataSetInterface::COL_PRODUCT_ABSTRACT_IDS] as $idProductAbstract) {
            $productLabelAbstractProductEntity = SpyProductLabelProductAbstractQuery::create()
                ->filterByFkProductLabel($dataSet[ProductLabelDataSetInterface::COL_ID_PRODUCT_LABEL])
                ->filterByFkProductAbstract($idProductAbstract)
                ->findOneOrCreate();

            if ($productLabelAbstractProductEntity->isNew() || $productLabelAbstractProductEntity->isModified()) {
                $productLabelAbstractProductEntity->save();

                $this->addPublishEvents(ProductLabelStorageConfig::PRODUCT_LABEL_PRODUCT_ABSTRACT_PUBLISH, $idProductAbstract);
                $this->addPublishEvents(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, $idProductAbstract);
            }
        }
    }
}
