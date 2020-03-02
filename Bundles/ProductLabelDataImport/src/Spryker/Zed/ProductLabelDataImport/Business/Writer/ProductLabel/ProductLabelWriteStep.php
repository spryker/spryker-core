<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelDataImport\Business\Writer\ProductLabel;

use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelTableMap;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductLabelDataImport\Business\Writer\ProductLabel\DataSet\ProductLabelDataSetInterface;

class ProductLabelWriteStep extends PublishAwareStep implements DataImportStepInterface
{
    public const COL_MAX_POSITION = 'max_position';

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
            ->setFrontEndReference($dataSet[ProductLabelDataSetInterface::COL_FRONT_END_REFERENCE]);

        if ($dataSet[ProductLabelDataSetInterface::COL_VALID_FROM]) {
            $productLabelEntity->setValidFrom($dataSet[ProductLabelDataSetInterface::COL_VALID_FROM]);
        }

        if ($dataSet[ProductLabelDataSetInterface::COL_VALID_TO]) {
            $productLabelEntity->setValidTo($dataSet[ProductLabelDataSetInterface::COL_VALID_TO]);
        }

        if ($productLabelEntity->isNew()) {
            $productLabelEntity->setPosition($this->getPosition());
        }

        if ($productLabelEntity->isNew() || $productLabelEntity->isModified()) {
            $productLabelEntity->save();
        }

        $dataSet[ProductLabelDataSetInterface::COL_ID_PRODUCT_LABEL] = $productLabelEntity->getIdProductLabel();
    }

    /**
     * @return int
     */
    protected function getPosition(): int
    {
        return SpyProductLabelQuery::create()
                ->withColumn(
                    sprintf('MAX(%s)', SpyProductLabelTableMap::COL_POSITION),
                    static::COL_MAX_POSITION
                )
                ->select([
                    static::COL_MAX_POSITION,
                ])
                ->findOne() + 1;
    }
}
