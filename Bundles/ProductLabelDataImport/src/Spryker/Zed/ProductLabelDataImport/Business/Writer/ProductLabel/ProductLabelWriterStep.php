<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelDataImport\Business\Writer\ProductLabel;

use Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductLabelDataImport\Business\Writer\ProductLabel\DataSet\ProductLabelDataSetInterface;

class ProductLabelWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productLabelEntity = SpyProductLabelQuery::create()
            ->filterByName($dataSet[ProductLabelDataSetInterface::COL_NAME])
            ->findOneOrCreate();

        $productLabelEntity
            ->setIsActive($dataSet[ProductLabelDataSetInterface::COL_IS_ACTIVE])
            ->setIsDynamic($dataSet[ProductLabelDataSetInterface::COL_IS_DYNAMIC])
            ->setIsExclusive($dataSet[ProductLabelDataSetInterface::COL_IS_EXCLUSIVE])
            ->setFrontEndReference($dataSet[ProductLabelDataSetInterface::COL_FRONT_END_REFERENCE])
            ->setPosition($dataSet[ProductLabelDataSetInterface::COL_PRIORITY] ?? 0);

        if ($dataSet[ProductLabelDataSetInterface::COL_VALID_FROM]) {
            $productLabelEntity->setValidFrom($dataSet[ProductLabelDataSetInterface::COL_VALID_FROM]);
        }

        if ($dataSet[ProductLabelDataSetInterface::COL_VALID_TO]) {
            $productLabelEntity->setValidTo($dataSet[ProductLabelDataSetInterface::COL_VALID_TO]);
        }

        if ($productLabelEntity->isNew() || $productLabelEntity->isModified()) {
            $productLabelEntity->save();
        }

        $dataSet[ProductLabelDataSetInterface::COL_ID_PRODUCT_LABEL] = $productLabelEntity->getIdProductLabel();
    }
}
