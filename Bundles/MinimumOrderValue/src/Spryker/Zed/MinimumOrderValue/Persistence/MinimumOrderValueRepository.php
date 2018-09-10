<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Persistence;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\MinimumOrderValue\Persistence\Map\SpyMinimumOrderValueTaxSetTableMap;
use Orm\Zed\Tax\Persistence\Map\SpyTaxRateTableMap;
use Orm\Zed\Tax\Persistence\Map\SpyTaxSetTableMap;
use Spryker\Shared\MinimumOrderValue\MinimumOrderValueConfig;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\MinimumOrderValue\Business\Strategy\Exception\MinimumOrderValueTypeNotFoundException;

/**
 * @method \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValuePersistenceFactory getFactory()
 */
class MinimumOrderValueRepository extends AbstractRepository implements MinimumOrderValueRepositoryInterface
{
    protected const COL_MAX_TAX_RATE = 'MaxTaxRate';

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategy\Exception\MinimumOrderValueTypeNotFoundException
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer
     */
    public function getMinimumOrderValueTypeByKey(
        MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer
    ): MinimumOrderValueTypeTransfer {
        $minimumOrderValueTypeTransfer->requireKey();

        $minimumOrderValueTypeEntity = $this->getFactory()
            ->createMinimumOrderValueTypeQuery()
            ->filterByKey($minimumOrderValueTypeTransfer->getKey())
            ->findOne();

        if (!$minimumOrderValueTypeEntity) {
            throw new MinimumOrderValueTypeNotFoundException($minimumOrderValueTypeTransfer->getKey());
        }

        return $this->getFactory()->createMinimumOrderValueMapper()
            ->mapMinimumOrderValueTypeEntityToTransfer(
                $minimumOrderValueTypeEntity,
                $minimumOrderValueTypeTransfer
            );
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer[]
     */
    public function getMinimumOrderValues(
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer
    ): array {
        $minimumOrderValueEntities = $this->getFactory()
            ->createMinimumOrderValueQuery()
            ->filterByStoreTransfer($storeTransfer)
            ->filterByCurrencyTransfer($currencyTransfer)
            ->joinWithMinimumOrderValueType()
            ->joinWithStore()
            ->joinWithCurrency()
            ->find();

        $minimumOrderValueTransfers = [];

        $minimumOrderValueMapper = $this->getFactory()->createMinimumOrderValueMapper();

        foreach ($minimumOrderValueEntities as $globalMinOrderValueEntity) {
            $minimumOrderValueTransfer = $minimumOrderValueMapper->mapMinimumOrderValueEntityToTransfer(
                $globalMinOrderValueEntity,
                new MinimumOrderValueTransfer()
            );

            $minimumOrderValueTransfers[] = $minimumOrderValueTransfer;
        }

        return $minimumOrderValueTransfers;
    }

    /**
     * @return int|null
     */
    public function findMinimumOrderValueTaxSetId(): ?int
    {
        $taxSetId = $this->getFactory()
            ->createMinimumOrderValueTaxSetPropelQuery()
            ->select([SpyMinimumOrderValueTaxSetTableMap::COL_FK_TAX_SET])
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
        return $this->getFactory()->createMinimumOrderValueTaxSetPropelQuery()
            ->useTaxSetQuery()
            ->useSpyTaxSetTaxQuery()
            ->useSpyTaxRateQuery()
            ->useCountryQuery()
            ->filterByIso2Code($countryIso2Code)
            ->endUse()
            ->_or()
            ->filterByName(MinimumOrderValueConfig::TAX_EXEMPT_PLACEHOLDER)
            ->endUse()
            ->endUse()
            ->groupBy(SpyTaxSetTableMap::COL_NAME)
            ->withColumn('MAX(' . SpyTaxRateTableMap::COL_RATE . ')', static::COL_MAX_TAX_RATE)
            ->endUse()
            ->select([static::COL_MAX_TAX_RATE])
            ->findOne();
    }
}
