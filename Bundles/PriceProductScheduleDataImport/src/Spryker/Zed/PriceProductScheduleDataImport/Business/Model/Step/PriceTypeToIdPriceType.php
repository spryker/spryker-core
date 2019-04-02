<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step;

use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\DataSet\PriceProductScheduleDataSet;

class PriceTypeToIdPriceType implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $idPriceProductTypeCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $priceProductType = $dataSet[PriceProductScheduleDataSet::KEY_PRICE_TYPE];
        if (!isset($this->idPriceProductTypeCache[$priceProductType])) {
            $priceProductScheduleEntity = $this->createSpyPriceTypeQuery()
                ->filterByName($priceProductType)
                ->findOneOrCreate();

            if ($priceProductScheduleEntity->isNew() || $priceProductScheduleEntity->isModified()) {
                $priceProductScheduleEntity->setPriceModeConfiguration(SpyPriceTypeTableMap::COL_PRICE_MODE_CONFIGURATION_BOTH);
                $priceProductScheduleEntity->save();
            }

            $this->idPriceProductTypeCache[$priceProductType] = $priceProductScheduleEntity->getIdPriceType();
        }

        $dataSet[PriceProductScheduleDataSet::FK_PRICE_TYPE] = $this->idPriceProductTypeCache[$priceProductType];
    }

    /**
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery
     */
    protected function createSpyPriceTypeQuery(): SpyPriceTypeQuery
    {
        return SpyPriceTypeQuery::create();
    }
}
