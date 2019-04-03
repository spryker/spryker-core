<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step;

use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\DataSet\PriceProductScheduleDataSetInterface;

class PriceTypeToIdPriceTypeStep implements DataImportStepInterface
{
    protected const EXCEPTION_MESSAGE = 'Could not find price type by name "%s"';

    /**
     * @var int[]
     */
    protected $idPriceProductTypeCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $priceProductType = $dataSet[PriceProductScheduleDataSetInterface::KEY_PRICE_TYPE];
        if (!isset($this->idPriceProductTypeCache[$priceProductType])) {
            $idPriceType = $this->createSpyPriceTypeQuery()
                ->select(SpyPriceTypeTableMap::COL_ID_PRICE_TYPE)
                ->findOneByName($priceProductType);

            if ($idPriceType === null) {
                throw new EntityNotFoundException(sprintf(static::EXCEPTION_MESSAGE, $priceProductType));
            }

            $this->idPriceProductTypeCache[$priceProductType] = $idPriceType;
        }

        $dataSet[PriceProductScheduleDataSetInterface::FK_PRICE_TYPE] = $this->idPriceProductTypeCache[$priceProductType];
    }

    /**
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery
     */
    protected function createSpyPriceTypeQuery(): SpyPriceTypeQuery
    {
        return SpyPriceTypeQuery::create();
    }
}
