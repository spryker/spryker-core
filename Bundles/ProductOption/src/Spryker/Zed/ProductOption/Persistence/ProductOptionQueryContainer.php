<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Propel\Runtime\ActiveQuery\Criteria;

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
     * @param string $sku
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery
     */
    public function queryProductOptionValueBySku($sku)
    {
        return $this->getFactory()
            ->createProductOptionValueQuery()
            ->filterBySku($sku);
    }

    /**
     * @param int $idProductOptionGroup
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroupQuery
     */
    public function queryProductOptionGroupById($idProductOptionGroup)
    {
        return $this->getFactory()
            ->createProductOptionGroupQuery()
            ->filterByIdProductOptionGroup($idProductOptionGroup);
    }

    /**
     * @param int $idProductOptionGroup
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductAbstractProductOptionGroupQuery
     */
    public function queryAbstractProductsByOptionGroupId($idProductOptionGroup, LocaleTransfer $localeTransfer)
    {
        return $this->getFactory()
            ->createProductAbstractProductOptionGroupQuery()
            ->innerJoinSpyProductAbstract()
            ->addJoin(
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                SpyProductAbstractLocalizedAttributesTableMap::COL_FK_PRODUCT_ABSTRACT,
                Criteria::INNER_JOIN
            )
            ->addJoin(
                SpyProductAbstractLocalizedAttributesTableMap::COL_FK_LOCALE,
                SpyLocaleTableMap::COL_ID_LOCALE,
                Criteria::INNER_JOIN
            )
            ->addAnd(
                SpyLocaleTableMap::COL_ID_LOCALE,
                $localeTransfer->getIdLocale(),
                Criteria::EQUAL
            )
            ->addAnd(
                SpyLocaleTableMap::COL_IS_ACTIVE,
                true,
                Criteria::EQUAL
            )
            ->withColumn(
                SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
                'name'
            )
            ->withColumn(
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                'id_product_abstract'
            )
            ->withColumn(
                SpyProductAbstractTableMap::COL_ATTRIBUTES,
                'abstract_attributes'
            )
            ->withColumn(
                SpyProductAbstractLocalizedAttributesTableMap::COL_ATTRIBUTES,
                'abstract_localized_attributes'
            )
            ->withColumn(
                SpyProductAbstractTableMap::COL_SKU,
                'sku'
            )
            ->filterByFkProductOptionGroup($idProductOptionGroup);
    }

    /**
     * @param string $term
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductsAbstractBySearchTerm($term, LocaleTransfer $localeTransfer)
    {
        $query = $this->getFactory()->createProductAbstractQuery();

        $query->addJoin(
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
            SpyProductAbstractLocalizedAttributesTableMap::COL_FK_PRODUCT_ABSTRACT,
            Criteria::INNER_JOIN
        )
            ->addJoin(
                SpyProductAbstractLocalizedAttributesTableMap::COL_FK_LOCALE,
                SpyLocaleTableMap::COL_ID_LOCALE,
                Criteria::INNER_JOIN
            )
            ->addAnd(
                SpyLocaleTableMap::COL_ID_LOCALE,
                $localeTransfer->getIdLocale(),
                Criteria::EQUAL
            )
            ->addAnd(
                SpyLocaleTableMap::COL_IS_ACTIVE,
                true,
                Criteria::EQUAL
            )
            ->withColumn(
                SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
                'name'
            )
            ->withColumn(
                SpyProductAbstractTableMap::COL_ATTRIBUTES,
                'abstract_attributes'
            )
            ->withColumn(
                SpyProductAbstractLocalizedAttributesTableMap::COL_ATTRIBUTES,
                'abstract_localized_attributes'
            );

        $query->groupByAttributes();
        $query->groupByIdProductAbstract();

        if (trim($term) !== '') {
            $term = '%' . mb_strtoupper($term) . '%';

            $query->where('UPPER(' . SpyProductAbstractTableMap::COL_SKU . ') LIKE ?', $term, \PDO::PARAM_STR)
                ->_or()
                ->where('UPPER(' . SpyProductAbstractLocalizedAttributesTableMap::COL_NAME . ') LIKE ?', $term, \PDO::PARAM_STR);
        }

        return $query;
    }

    /**
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroupQuery
     */
    public function queryProductOptionGroupWithValues()
    {
        return $this->getFactory()->createProductOptionGroupQuery()
            ->joinSpyProductOptionValue()
            ->groupByIdProductOptionGroup();
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
