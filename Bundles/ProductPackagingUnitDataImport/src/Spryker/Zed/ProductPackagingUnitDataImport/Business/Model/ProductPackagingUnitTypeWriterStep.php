<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitDataImport\Business\Model;

use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductPackagingUnitDataImport\Business\Model\DataSet\ProductPackagingUnitDataSet;


class ProductPackagingUnitTypeWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productPackagingUnitTypeEntity = SpyProductPackagingUnitTypeQuery::create()
            ->filterByName($dataSet[ProductPackagingUnitDataSet::TYPE_NAME])
            ->findOneOrCreate();

        $productPackagingUnitTypeEntity
            ->setName($dataSet[ProductPackagingUnitDataSet::TYPE_NAME])
            ->save();
    }
}
