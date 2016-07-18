<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionPersistenceFactory getFactory()
 */
class ProductOptionQueryContainer extends AbstractQueryContainer implements ProductOptionQueryContainerInterface
{

    const COL_MAX_TAX_RATE = 'MaxTaxRate';
    const COL_ID_PRODUCT_OPTION_VALUE_USAGE = 'IdProductOptionValueUsage';

    /**
     * @param int $idProductOptionGroup
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroupQuery
     */
    public function queryProductOptionGroupByIdProductOptionGroup($idProductOptionGroup)
    {
        return $this->getFactory()
            ->createProductOptionGroupQuery()
            ->filterByIdProductOptionGroup($idProductOptionGroup);
    }


    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractBySku($sku)
    {
        return $this->getFactory()
            ->createProductAbstractQuery()
            ->filterBySku($sku);
    }

    /**
     * @param int $idProductOptionValue
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery
     */
    public function queryProductOptionByValueId($idProductOptionValue)
    {
        return $this->getFactory()
            ->createProductOptionValueQuery()
            ->filterByIdProductOptionValue($idProductOptionValue);
    }

    /**
     *
     * @param int[] $allIdOptionValueUsages
     * @param string $countryIso2Code
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function queryTaxSetByIdProductOptionValueUsagesAndCountryIso2Code($allIdOptionValueUsages, $countryIso2Code)
    {
        /*return $this->getFactory()->createProductOptionValueUsageQuery()
            ->filterByIdProductOptionValueUsage($allIdOptionValueUsages, Criteria::IN)
            ->withColumn(SpyProductOptionValueUsageTableMap::COL_ID_PRODUCT_OPTION_VALUE_USAGE, self::COL_ID_PRODUCT_OPTION_VALUE_USAGE)
            ->groupBy(SpyProductOptionValueUsageTableMap::COL_ID_PRODUCT_OPTION_VALUE_USAGE)
            ->useSpyProductOptionTypeUsageQuery()
                ->useSpyProductOptionTypeQuery()
                    ->useSpyTaxSetQuery()
                        ->useSpyTaxSetTaxQuery()
                            ->useSpyTaxRateQuery()
                                ->useCountryQuery()
                                    ->filterByIso2Code($countryIso2Code)
                                ->endUse()
                                ->_or()
                                ->filterByName(TaxConstants::TAX_EXEMPT_PLACEHOLDER)
                            ->endUse()
                        ->endUse()
                        ->withColumn(SpyTaxSetTableMap::COL_NAME)
                        ->groupBy(SpyTaxSetTableMap::COL_NAME)
                    ->endUse()
                    ->withColumn('MAX(' . SpyTaxRateTableMap::COL_RATE . ')', self::COL_MAX_TAX_RATE)
                ->endUse()
            ->endUse()
            ->select([self::COL_MAX_TAX_RATE]);*/
    }

}
