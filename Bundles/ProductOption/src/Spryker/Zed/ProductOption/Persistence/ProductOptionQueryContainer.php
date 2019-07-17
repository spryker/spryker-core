<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductOptionCriteriaTransfer;
use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductAbstractProductOptionGroupTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionValueTableMap;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionGroupQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery;
use Orm\Zed\Tax\Persistence\Map\SpyTaxRateTableMap;
use Orm\Zed\Tax\Persistence\Map\SpyTaxSetTableMap;
use PDO;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Shared\Tax\TaxConstants;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionPersistenceFactory getFactory()
 */
class ProductOptionQueryContainer extends AbstractQueryContainer implements ProductOptionQueryContainerInterface
{
    public const COL_MAX_TAX_RATE = 'MaxTaxRate';
    public const COL_ID_PRODUCT_OPTION_VALUE = 'idProductOptionValue';

    /**
     * @api
     *
     * @param string $sku
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
     * @api
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductAbstractProductOptionGroupQuery
     */
    public function queryAllProductAbstractProductOptionGroups()
    {
        return $this->getFactory()
            ->createProductAbstractProductOptionGroupQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroupQuery
     */
    public function queryAllProductOptionGroups()
    {
        return $this->getFactory()
            ->createProductOptionGroupQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery
     */
    public function queryAllProductOptionValues()
    {
        return $this->getFactory()
            ->createProductOptionValueQuery();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function querySalesOrder()
    {
        return $this->getFactory()
            ->getSalesQueryContainer()
            ->querySalesOrderItem();
    }

    /**
     * @api
     *
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
     * @api
     *
     * @param int $idProductOptionValue
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroupQuery
     */
    public function queryProductOptionGroupByProductOptionValueId(int $idProductOptionValue): SpyProductOptionGroupQuery
    {
        return $this->getFactory()
            ->createProductOptionGroupQuery()
            ->filterByActive(true)
            ->useSpyProductOptionValueQuery()
                ->filterByIdProductOptionValue($idProductOptionValue)
            ->endUse();
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOptionCriteriaTransfer $productOptionCriteriaTransfer
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery
     */
    public function queryProductOptionByProductOptionCriteria(ProductOptionCriteriaTransfer $productOptionCriteriaTransfer): SpyProductOptionValueQuery
    {
        $productOptionCriteriaTransfer->requireProductOptionIds();
        $productOptionValueQuery = $this->getFactory()
            ->createProductOptionValueQuery();

        $productOptionValueQuery = $this->applyProductOptionCriteriaFilter(
            $productOptionCriteriaTransfer,
            $productOptionValueQuery
        );

        return $productOptionValueQuery;
    }

    /**
     * @api
     *
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
     * @api
     *
     * @param string $value
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery
     */
    public function queryProductOptionValue($value)
    {
        return $this->getFactory()
            ->createProductOptionValueQuery()
            ->filterByValue($value);
    }

    /**
     * @api
     *
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
     * @api
     *
     * @param int $idProductOptionGroup
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroupQuery
     */
    public function queryProductOptionGroupWithProductOptionValuesAndProductOptionValuePricesById($idProductOptionGroup)
    {
        return $this->queryProductOptionGroupById($idProductOptionGroup)
            ->leftJoinWithSpyProductOptionValue()
            ->useSpyProductOptionValueQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinWithProductOptionValuePrice()
                ->orderByIdProductOptionValue()
                ->useProductOptionValuePriceQuery(null, Criteria::LEFT_JOIN)
                    ->orderByFkStore()
                    ->orderByFkCurrency()
                ->endUse()
            ->endUse();
    }

    /**
     * @api
     *
     * @param int $idProductOptionGroup
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroupQuery
     */
    public function queryActiveProductOptionGroupWithProductOptionValuesAndProductOptionValuePricesById($idProductOptionGroup)
    {
        return $this->queryProductOptionGroupWithProductOptionValuesAndProductOptionValuePricesById($idProductOptionGroup)
            ->filterByActive(true);
    }

    /**
     * @api
     *
     * @param int $idProductOptionValue
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValuePriceQuery
     */
    public function queryProductOptionValuePricesByIdProductOptionValue($idProductOptionValue)
    {
        return $this->getFactory()
            ->createProductOptionValuePriceQuery()
            ->filterByFkProductOptionValue($idProductOptionValue);
    }

    /**
     * @api
     *
     * @param string $groupName
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroupQuery
     */
    public function queryProductOptionGroupByName($groupName)
    {
        return $this->getFactory()
            ->createProductOptionGroupQuery()
            ->filterByName($groupName);
    }

    /**
     * @api
     *
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
     * @api
     *
     * @param string $term
     * @param int $idProductOptionGroup
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductsAbstractBySearchTermForAssignment($term, $idProductOptionGroup, LocaleTransfer $localeTransfer)
    {
        $query = $this->queryProductsAbstractBySearchTerm($term, $localeTransfer);

        $query->addJoin(
            [SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, $idProductOptionGroup],
            [SpyProductAbstractProductOptionGroupTableMap::COL_FK_PRODUCT_ABSTRACT, SpyProductAbstractProductOptionGroupTableMap::COL_FK_PRODUCT_OPTION_GROUP],
            Criteria::LEFT_JOIN
        )
            ->addAnd(
                SpyProductAbstractProductOptionGroupTableMap::COL_FK_PRODUCT_OPTION_GROUP,
                null,
                Criteria::ISNULL
            );

        return $query;
    }

    /**
     * @api
     *
     * @param string $term
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function queryProductsAbstractBySearchTerm($term, LocaleTransfer $localeTransfer)
    {
        $query = $this->getFactory()
            ->createProductAbstractQuery();

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

        $term = trim($term);
        if ($term !== '') {
            $term = '%' . mb_strtoupper($term) . '%';

            $query->where('UPPER(' . SpyProductAbstractTableMap::COL_SKU . ') LIKE ?', $term, PDO::PARAM_STR)
                ->_or()
                ->where('UPPER(' . SpyProductAbstractLocalizedAttributesTableMap::COL_NAME . ') LIKE ?', $term, PDO::PARAM_STR);
        }

        return $query;
    }

    /**
     * @api
     *
     * @param int[] $allIdOptionValueUsages
     * @param string $countryIso2Code
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryTaxSetByIdProductOptionValueAndCountryIso2Code($allIdOptionValueUsages, $countryIso2Code)
    {
        $countryEntity = $this->queryCountryByIso2Code($countryIso2Code)
            ->findOne();

        return $this->getFactory()->createProductOptionValueQuery()
            ->filterByIdProductOptionValue($allIdOptionValueUsages, Criteria::IN)
            ->withColumn(SpyProductOptionValueTableMap::COL_ID_PRODUCT_OPTION_VALUE, self::COL_ID_PRODUCT_OPTION_VALUE)
            ->groupBy(SpyProductOptionValueTableMap::COL_ID_PRODUCT_OPTION_VALUE)
            ->useSpyProductOptionGroupQuery()
                ->useSpyTaxSetQuery()
                    ->useSpyTaxSetTaxQuery()
                        ->useSpyTaxRateQuery()
                              ->filterByFkCountry($countryEntity->getIdCountry())
                            ->_or()
                               ->filterByName(TaxConstants::TAX_EXEMPT_PLACEHOLDER)
                        ->endUse()
                    ->endUse()
                    ->withColumn(SpyTaxSetTableMap::COL_NAME)
                    ->groupBy(SpyTaxSetTableMap::COL_NAME)
                ->endUse()
                ->withColumn('MAX(' . SpyTaxRateTableMap::COL_RATE . ')', self::COL_MAX_TAX_RATE)
            ->endUse()
            ->select([self::COL_MAX_TAX_RATE]);
    }

    /**
     * @param string $countryIso2Code
     *
     * @return \Orm\Zed\Country\Persistence\SpyCountryQuery
     */
    protected function queryCountryByIso2Code($countryIso2Code)
    {
        return $this->getFactory()
            ->getCountryQueryContainer()
            ->queryCountries()
            ->filterByIso2Code($countryIso2Code);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionCriteriaTransfer $productOptionCriteriaTransfer
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery $productOptionValueQuery
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery
     */
    protected function applyProductOptionCriteriaFilter(
        ProductOptionCriteriaTransfer $productOptionCriteriaTransfer,
        SpyProductOptionValueQuery $productOptionValueQuery
    ): SpyProductOptionValueQuery {
        $productOptionValueQuery = $this->filterProductOptionGroupByActiveField(
            $productOptionCriteriaTransfer,
            $productOptionValueQuery
        );

        $productOptionValueQuery = $this->filterProductOptionGroupByProductConcreteSku(
            $productOptionCriteriaTransfer,
            $productOptionValueQuery
        );

        return $productOptionValueQuery->filterByIdProductOptionValue_In(
            $productOptionCriteriaTransfer->getProductOptionIds()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionCriteriaTransfer $productOptionCriteriaTransfer
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery $productOptionValueQuery
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery
     */
    protected function filterProductOptionGroupByActiveField(
        ProductOptionCriteriaTransfer $productOptionCriteriaTransfer,
        SpyProductOptionValueQuery $productOptionValueQuery
    ): SpyProductOptionValueQuery {
        $productOptionGroupIsActive = $productOptionCriteriaTransfer->getProductOptionGroupIsActive();

        if ($productOptionGroupIsActive === null) {
            return $productOptionValueQuery;
        }

        $productOptionValueQuery
            ->useSpyProductOptionGroupQuery()
                ->filterByActive($productOptionGroupIsActive)
            ->endUse();

        return $productOptionValueQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionCriteriaTransfer $productOptionCriteriaTransfer
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery $productOptionValueQuery
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery
     */
    protected function filterProductOptionGroupByProductConcreteSku(
        ProductOptionCriteriaTransfer $productOptionCriteriaTransfer,
        SpyProductOptionValueQuery $productOptionValueQuery
    ): SpyProductOptionValueQuery {
        $productConcreteSku = $productOptionCriteriaTransfer->getProductConcreteSku();

        if (!$productConcreteSku) {
            return $productOptionValueQuery;
        }

        $productOptionValueQuery
            ->useSpyProductOptionGroupQuery()
                ->useSpyProductAbstractProductOptionGroupQuery(null, Criteria::LEFT_JOIN)
                    ->useSpyProductAbstractQuery()
                        ->useSpyProductQuery()
                            ->filterBySku($productConcreteSku)
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->endUse();

        return $productOptionValueQuery;
    }
}
