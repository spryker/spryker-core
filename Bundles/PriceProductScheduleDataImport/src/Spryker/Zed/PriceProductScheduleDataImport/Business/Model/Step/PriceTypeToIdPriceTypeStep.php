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
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\DataSet\PriceProductScheduleDataSetInterface;

class PriceTypeToIdPriceTypeStep implements DataImportStepInterface
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
        $priceProductTypeName = $dataSet[PriceProductScheduleDataSetInterface::KEY_PRICE_TYPE];
        $priceProductTypeName = $this->preparePriceProductTypeName($priceProductTypeName);

        if (!isset($this->idPriceProductTypeCache[$priceProductTypeName])) {
            $priceTypeEntity = $this->createSpyPriceTypeQuery()
                ->filterByName($priceProductTypeName)
                ->findOneOrCreate();

            if ($priceTypeEntity->isNew() || $priceTypeEntity->isModified()) {
                $priceTypeEntity->setPriceModeConfiguration(SpyPriceTypeTableMap::COL_PRICE_MODE_CONFIGURATION_BOTH);
                $priceTypeEntity->save();
            }

            $this->idPriceProductTypeCache[$priceProductTypeName] = $priceTypeEntity->getIdPriceType();
        }

        $dataSet[PriceProductScheduleDataSetInterface::FK_PRICE_TYPE] = $this->idPriceProductTypeCache[$priceProductTypeName];
    }

    /**
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery
     */
    protected function createSpyPriceTypeQuery(): SpyPriceTypeQuery
    {
        return SpyPriceTypeQuery::create();
    }

    /**
     * @param string $priceProductTypeName
     *
     * @return string
     */
    protected function preparePriceProductTypeName(string $priceProductTypeName): string
    {
        return trim($priceProductTypeName);
    }
}
