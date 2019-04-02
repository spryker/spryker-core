<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleDataImport\Business\Model;

use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\DataSet\PriceProductScheduleDataSet;

class PriceProductScheduleWriterStep implements DataImportStepInterface
{
    private const EXCEPTION_MESSAGE = 'One of "%s" or "%s" must be in the data set. Given: "%s"';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $priceProductScheduleQuery = $this->createPriceProductScheduleQuery()
            ->filterByFkPriceType($dataSet[PriceProductScheduleDataSet::FK_PRICE_TYPE])
            ->filterByFkStore($dataSet[PriceProductScheduleDataSet::FK_STORE])
            ->filterByFkCurrency($dataSet[PriceProductScheduleDataSet::FK_CURRENCY])
            ->filterByFkPriceProductScheduleList($dataSet[PriceProductScheduleDataSet::FK_PRICE_PRODUCT_SCHEDULE_LIST])
            ->filterByNetPrice($dataSet[PriceProductScheduleDataSet::KEY_PRICE_NET])
            ->filterByGrossPrice($dataSet[PriceProductScheduleDataSet::KEY_PRICE_GROSS])
            ->filterByActiveFrom($dataSet[PriceProductScheduleDataSet::KEY_INCLUDED_FROM])
            ->filterByActiveTo($dataSet[PriceProductScheduleDataSet::KEY_INCLUDED_TO]);

        if (empty($dataSet[PriceProductScheduleDataSet::FK_PRODUCT_ABSTRACT]) && empty($dataSet[PriceProductScheduleDataSet::FK_PRODUCT_CONCRETE])) {
            throw new DataKeyNotFoundInDataSetException(sprintf(
                self::EXCEPTION_MESSAGE,
                PriceProductScheduleDataSet::KEY_ABSTRACT_SKU,
                PriceProductScheduleDataSet::KEY_CONCRETE_SKU,
                implode(', ', array_keys($dataSet->getArrayCopy()))
            ));
        }

        if (!empty($dataSet[PriceProductScheduleDataSet::FK_PRODUCT_ABSTRACT])) {
            $priceProductScheduleQuery->filterByFkProductAbstract($dataSet[PriceProductScheduleDataSet::FK_PRODUCT_ABSTRACT]);
        }

        if (!empty($dataSet[PriceProductScheduleDataSet::FK_PRODUCT_CONCRETE])) {
            $priceProductScheduleQuery->filterByFkProduct($dataSet[PriceProductScheduleDataSet::FK_PRODUCT_CONCRETE]);
        }

        $priceProductScheduleEntity = $priceProductScheduleQuery->findOneOrCreate();

        if ($priceProductScheduleEntity->isNew()) {
            return;
        }

        $priceProductScheduleEntity
            ->setFkStore($dataSet[PriceProductScheduleDataSet::FK_STORE])
            ->setFkCurrency($dataSet[PriceProductScheduleDataSet::FK_CURRENCY])
            ->setFkPriceProductScheduleList($dataSet[PriceProductScheduleDataSet::FK_PRICE_PRODUCT_SCHEDULE_LIST])
            ->setNetPrice($dataSet[PriceProductScheduleDataSet::KEY_PRICE_NET])
            ->setGrossPrice($dataSet[PriceProductScheduleDataSet::KEY_PRICE_GROSS])
            ->setActiveFrom($dataSet[PriceProductScheduleDataSet::KEY_INCLUDED_FROM])
            ->setActiveTo($dataSet[PriceProductScheduleDataSet::KEY_INCLUDED_TO])
            ->setIsCurrent(false)
            ->save();
    }

    /**
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    protected function createPriceProductScheduleQuery(): SpyPriceProductScheduleQuery
    {
        return SpyPriceProductScheduleQuery::create();
    }
}
