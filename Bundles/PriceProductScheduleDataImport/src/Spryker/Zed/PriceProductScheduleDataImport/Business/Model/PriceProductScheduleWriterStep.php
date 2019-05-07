<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleDataImport\Business\Model;

use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\DataSet\PriceProductScheduleDataSetInterface;

class PriceProductScheduleWriterStep implements DataImportStepInterface
{
    protected const EXCEPTION_MESSAGE = 'One of "%s" or "%s" must be in the data set. Given: "%s"';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (empty($dataSet[PriceProductScheduleDataSetInterface::FK_PRODUCT_ABSTRACT]) && empty($dataSet[PriceProductScheduleDataSetInterface::FK_PRODUCT_CONCRETE])) {
            throw new DataKeyNotFoundInDataSetException(sprintf(
                static::EXCEPTION_MESSAGE,
                PriceProductScheduleDataSetInterface::KEY_ABSTRACT_SKU,
                PriceProductScheduleDataSetInterface::KEY_CONCRETE_SKU,
                implode(', ', array_keys($dataSet->getArrayCopy()))
            ));
        }

        $priceProductScheduleEntity = $this->createPriceProductScheduleQuery($dataSet)->findOneOrCreate();

        if ($priceProductScheduleEntity->isNew() === false) {
            return;
        }

        $this->savePriceProductSchedule($priceProductScheduleEntity, $dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleQuery
     */
    protected function createPriceProductScheduleQuery(DataSetInterface $dataSet): SpyPriceProductScheduleQuery
    {
        $priceProductScheduleQuery = SpyPriceProductScheduleQuery::create();

        $priceProductScheduleQuery
            ->filterByFkPriceType($dataSet[PriceProductScheduleDataSetInterface::FK_PRICE_TYPE])
            ->filterByFkStore($dataSet[PriceProductScheduleDataSetInterface::FK_STORE])
            ->filterByFkCurrency($dataSet[PriceProductScheduleDataSetInterface::FK_CURRENCY])
            ->filterByNetPrice($dataSet[PriceProductScheduleDataSetInterface::KEY_PRICE_NET])
            ->filterByGrossPrice($dataSet[PriceProductScheduleDataSetInterface::KEY_PRICE_GROSS])
            ->filterByActiveFrom($dataSet[PriceProductScheduleDataSetInterface::KEY_INCLUDED_FROM])
            ->filterByActiveTo($dataSet[PriceProductScheduleDataSetInterface::KEY_INCLUDED_TO]);

        if (!empty($dataSet[PriceProductScheduleDataSetInterface::FK_PRODUCT_ABSTRACT])) {
            $priceProductScheduleQuery->filterByFkProductAbstract($dataSet[PriceProductScheduleDataSetInterface::FK_PRODUCT_ABSTRACT]);
        }

        if (!empty($dataSet[PriceProductScheduleDataSetInterface::FK_PRODUCT_CONCRETE])) {
            $priceProductScheduleQuery->filterByFkProduct($dataSet[PriceProductScheduleDataSetInterface::FK_PRODUCT_CONCRETE]);
        }

        return $priceProductScheduleQuery;
    }

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductSchedule $priceProductScheduleEntity
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function savePriceProductSchedule(
        SpyPriceProductSchedule $priceProductScheduleEntity,
        DataSetInterface $dataSet
    ): void {
        $priceProductScheduleEntity
            ->setFkStore($dataSet[PriceProductScheduleDataSetInterface::FK_STORE])
            ->setFkCurrency($dataSet[PriceProductScheduleDataSetInterface::FK_CURRENCY])
            ->setFkPriceProductScheduleList($dataSet[PriceProductScheduleDataSetInterface::FK_PRICE_PRODUCT_SCHEDULE_LIST])
            ->setNetPrice($dataSet[PriceProductScheduleDataSetInterface::KEY_PRICE_NET])
            ->setGrossPrice($dataSet[PriceProductScheduleDataSetInterface::KEY_PRICE_GROSS])
            ->setActiveFrom($dataSet[PriceProductScheduleDataSetInterface::KEY_INCLUDED_FROM])
            ->setActiveTo($dataSet[PriceProductScheduleDataSetInterface::KEY_INCLUDED_TO])
            ->setIsCurrent(false)
            ->save();
    }
}
