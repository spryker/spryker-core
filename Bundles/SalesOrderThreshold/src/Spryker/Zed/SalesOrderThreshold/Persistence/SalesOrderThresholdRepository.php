<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Persistence;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTransfer;
use Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\SalesOrderThreshold\Persistence\Map\SpySalesOrderThresholdTaxSetTableMap;
use Orm\Zed\Tax\Persistence\Map\SpyTaxRateTableMap;
use Orm\Zed\Tax\Persistence\Map\SpyTaxSetTableMap;
use Spryker\Shared\SalesOrderThreshold\SalesOrderThresholdConfig;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\SalesOrderThreshold\Business\Strategy\Exception\SalesOrderThresholdTypeNotFoundException;

/**
 * @method \Spryker\Zed\SalesOrderThreshold\Persistence\SalesOrderThresholdPersistenceFactory getFactory()
 */
class SalesOrderThresholdRepository extends AbstractRepository implements SalesOrderThresholdRepositoryInterface
{
    protected const COL_MAX_TAX_RATE = 'MaxTaxRate';

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer $salesOrderThresholdTypeTransfer
     *
     * @throws \Spryker\Zed\SalesOrderThreshold\Business\Strategy\Exception\SalesOrderThresholdTypeNotFoundException
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer
     */
    public function getSalesOrderThresholdTypeByKey(
        SalesOrderThresholdTypeTransfer $salesOrderThresholdTypeTransfer
    ): SalesOrderThresholdTypeTransfer {
        $salesOrderThresholdTypeTransfer->requireKey();

        $salesOrderThresholdTypeEntity = $this->getFactory()
            ->createSalesOrderThresholdTypeQuery()
            ->filterByKey($salesOrderThresholdTypeTransfer->getKey())
            ->findOne();

        if (!$salesOrderThresholdTypeEntity) {
            throw new SalesOrderThresholdTypeNotFoundException($salesOrderThresholdTypeTransfer->getKey());
        }

        return $this->getFactory()->createSalesOrderThresholdMapper()
            ->mapSalesOrderThresholdTypeEntityToTransfer(
                $salesOrderThresholdTypeEntity,
                $salesOrderThresholdTypeTransfer
            );
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer[]
     */
    public function getSalesOrderThresholds(
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): array {
        $salesOrderThresholdEntities = $this->getFactory()
            ->createSalesOrderThresholdQuery()
            ->filterByStoreTransfer($storeTransfer)
            ->filterByCurrencyTransfer($currencyTransfer)
            ->joinWithSalesOrderThresholdType()
            ->joinWithStore()
            ->joinWithCurrency()
            ->find();

        $salesOrderThresholdTransfers = [];

        $salesOrderThresholdMapper = $this->getFactory()->createSalesOrderThresholdMapper();

        foreach ($salesOrderThresholdEntities as $globalSalesOrderThresholdEntity) {
            $salesOrderThresholdTransfer = $salesOrderThresholdMapper->mapSalesOrderThresholdEntityToTransfer(
                $globalSalesOrderThresholdEntity,
                new SalesOrderThresholdTransfer()
            );

            $salesOrderThresholdTransfers[] = $salesOrderThresholdTransfer;
        }

        return $salesOrderThresholdTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTransfer $salesOrderThresholdTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTransfer|null
     */
    public function findSalesOrderThreshold(
        SalesOrderThresholdTransfer $salesOrderThresholdTransfer
    ): ?SalesOrderThresholdTransfer {
        $salesOrderThresholdEntity = $this->getFactory()
            ->createSalesOrderThresholdQuery()
            ->findOneByIdSalesOrderThreshold(
                $salesOrderThresholdTransfer->getIdSalesOrderThreshold()
            );

        if (!$salesOrderThresholdEntity) {
            return null;
        }

        return $this->getFactory()
            ->createSalesOrderThresholdMapper()
            ->mapSalesOrderThresholdEntityToTransfer(
                $salesOrderThresholdEntity,
                new SalesOrderThresholdTransfer()
            );
    }

    /**
     * @return int|null
     */
    public function findSalesOrderThresholdTaxSetId(): ?int
    {
        $taxSetId = $this->getFactory()
            ->createSalesOrderThresholdTaxSetPropelQuery()
            ->select([SpySalesOrderThresholdTaxSetTableMap::COL_FK_TAX_SET])
            ->findOne();

        return $taxSetId;
    }

    /**
     * @uses Tax
     * @uses Country
     *
     * @param string $countryIso2Code
     *
     * @return float|null
     */
    public function findMaxTaxRateByCountryIso2Code(string $countryIso2Code): ?float
    {
        return $this->getFactory()->createSalesOrderThresholdTaxSetPropelQuery()
            ->useTaxSetQuery()
            ->useSpyTaxSetTaxQuery()
            ->useSpyTaxRateQuery()
            ->useCountryQuery()
            ->filterByIso2Code($countryIso2Code)
            ->endUse()
            ->_or()
            ->filterByName(SalesOrderThresholdConfig::TAX_EXEMPT_PLACEHOLDER)
            ->endUse()
            ->endUse()
            ->groupBy(SpyTaxSetTableMap::COL_NAME)
            ->withColumn('MAX(' . SpyTaxRateTableMap::COL_RATE . ')', static::COL_MAX_TAX_RATE)
            ->endUse()
            ->select([static::COL_MAX_TAX_RATE])
            ->findOne();
    }
}
