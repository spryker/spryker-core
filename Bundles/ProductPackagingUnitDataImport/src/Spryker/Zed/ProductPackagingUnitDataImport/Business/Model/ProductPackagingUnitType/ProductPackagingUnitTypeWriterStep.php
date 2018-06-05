<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitDataImport\Business\Model\ProductPackagingUnitType;

use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductPackagingUnitDataImport\Business\Model\DataSet\ProductPackagingUnitTypeDataSetInterface;

class ProductPackagingUnitTypeWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productPackagingUnitTypeEntity = $this->getProductPackagingUnitTypeQuery()
            ->filterByName($dataSet[ProductPackagingUnitTypeDataSetInterface::NAME])
            ->findOneOrCreate();

        if ($productPackagingUnitTypeEntity->isNew()) {
            $productPackagingUnitTypeEntity->save();
        }
    }

    /**
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery
     */
    protected function getProductPackagingUnitTypeQuery(): SpyProductPackagingUnitTypeQuery
    {
        return SpyProductPackagingUnitTypeQuery::create();
    }
}
