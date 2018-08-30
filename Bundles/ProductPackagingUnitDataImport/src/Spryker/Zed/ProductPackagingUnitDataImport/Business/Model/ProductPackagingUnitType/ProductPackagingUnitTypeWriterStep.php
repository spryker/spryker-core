<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
            ->filterByName($dataSet[ProductPackagingUnitTypeDataSetInterface::COLUMN_NAME])
            ->findOneOrCreate();

        if ($productPackagingUnitTypeEntity->isNew()) {
            $productPackagingUnitTypeEntity->save();
        }
    }

    /**
     * @module ProductPackagingUnit
     *
     * @return \Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery
     */
    protected function getProductPackagingUnitTypeQuery(): SpyProductPackagingUnitTypeQuery
    {
        return SpyProductPackagingUnitTypeQuery::create();
    }
}
