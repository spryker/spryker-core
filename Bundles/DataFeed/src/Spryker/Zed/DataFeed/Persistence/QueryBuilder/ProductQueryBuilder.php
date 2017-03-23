<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataFeed\Persistence\QueryBuilder;

use Generated\Shared\Transfer\DataFeedConditionTransfer;
use Generated\Shared\Transfer\DataFeedDateFilterTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductFeedJoinTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\Price\Persistence\Map\SpyPriceProductTableMap;
use Orm\Zed\Price\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionGroupTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionValueTableMap;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductQueryBuilder extends QueryBuilderAbstract implements QueryBuilderInterface
{

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     */
    protected $productQueryContainer;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     */
    public function __construct(ProductQueryContainerInterface $productQueryContainer)
    {
        $this->productQueryContainer = $productQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\DataFeedConditionTransfer $dataFeedConditionTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function getDataFeed(DataFeedConditionTransfer $dataFeedConditionTransfer)
    {
        $productFeedJoinTransfer = $dataFeedConditionTransfer->getProductFeedJoin();
        $abstractProductQuery = $this->productQueryContainer
            ->queryProductAbstract();

        $abstractProductQuery->addSelfSelectColumns();
        $abstractProductQuery = $this->joinProductLocalizedAttributes($abstractProductQuery, $dataFeedConditionTransfer->getLocale());

        $abstractProductQuery = $this->applyJoins(
            $abstractProductQuery,
            $productFeedJoinTransfer,
            $dataFeedConditionTransfer->getLocale()
        );
        $abstractProductQuery = $this->applyLocaleFilter($abstractProductQuery, $dataFeedConditionTransfer->getLocale());
        $abstractProductQuery = $this->applyPagination($abstractProductQuery, $dataFeedConditionTransfer->getPagination());
        $abstractProductQuery = $this->applyDateFilter($abstractProductQuery, $dataFeedConditionTransfer->getDateFilter());

        $abstractProductQuery->groupBy(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT);

        return $abstractProductQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery $abstractProductQuery
     * @param \Generated\Shared\Transfer\DataFeedDateFilterTransfer $dataFeedDateFilterTransfer
     *
     * @return SpyProductAbstractQuery
     */
    protected function applyDateFilter(
        SpyProductAbstractQuery $abstractProductQuery,
        DataFeedDateFilterTransfer $dataFeedDateFilterTransfer = null
    ) {
        if ($dataFeedDateFilterTransfer !== null) {
            $abstractProductQuery = $this->joinTouchTable($abstractProductQuery);

            if ($dataFeedDateFilterTransfer->getUpdatedFrom() !== null) {
                $abstractProductQuery->condition(
                    'updatedFromCondition',
                    SpyTouchTableMap::COL_TOUCHED . '> ?',
                    $dataFeedDateFilterTransfer->getUpdatedFrom(),
                    \PDO::PARAM_STR
                );
                $abstractProductQuery->where(['updatedFromCondition']);
            }

            if ($dataFeedDateFilterTransfer->getUpdatedTo() !== null) {
                $abstractProductQuery->condition(
                    'updatedToCondition',
                    SpyTouchTableMap::COL_TOUCHED . '< ?',
                    $dataFeedDateFilterTransfer->getUpdatedTo(),
                    \PDO::PARAM_STR
                );
                $abstractProductQuery->where(['updatedToCondition']);
            }
        }

        return $abstractProductQuery;
    }

    /**
     * @param SpyProductAbstractQuery $abstractProductQuery
     *
     * @return SpyProductAbstractQuery
     */
    protected function joinTouchTable(SpyProductAbstractQuery $abstractProductQuery)
    {
        $abstractProductQuery->addJoin(
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
            SpyTouchTableMap::COL_ITEM_ID,
            Criteria::INNER_JOIN
        );
        $abstractProductQuery->condition(
            'joinCondition',
            SpyTouchTableMap::COL_ITEM_TYPE . '= ?',
            'product_abstract',
            \PDO::PARAM_STR
        );
        $abstractProductQuery->where(['joinCondition']);

        return $abstractProductQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery $abstractProductQuery
     * @param \Generated\Shared\Transfer\ProductFeedJoinTransfer $productFeedJoinTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return SpyProductAbstractQuery
     */
    protected function applyJoins(
        SpyProductAbstractQuery $abstractProductQuery,
        ProductFeedJoinTransfer $productFeedJoinTransfer = null,
        LocaleTransfer $localeTransfer = null
    ) {
        if ($productFeedJoinTransfer !== null) {
            $abstractProductQuery = $this->joinProductImages($abstractProductQuery, $productFeedJoinTransfer, $localeTransfer);
            $abstractProductQuery = $this->joinProductCategories($abstractProductQuery, $productFeedJoinTransfer, $localeTransfer);
            $abstractProductQuery = $this->joinProductPrices($abstractProductQuery, $productFeedJoinTransfer);
            $abstractProductQuery = $this->joinProductVariants($abstractProductQuery, $productFeedJoinTransfer, $localeTransfer);
            $abstractProductQuery = $this->joinProductOptions($abstractProductQuery, $productFeedJoinTransfer);
        }

        return $abstractProductQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery $abstractProductQuery
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return SpyProductAbstractQuery
     */
    protected function applyLocaleFilter(
        SpyProductAbstractQuery $abstractProductQuery,
        LocaleTransfer $localeTransfer = null
    ) {
        if ($localeTransfer !== null) {
            $conditions = $this->getLocaleFilterConditionsArray($localeTransfer);

            $abstractProductQuery
                ->useSpyProductAbstractLocalizedAttributesQuery()
                    ->useLocaleQuery()
                        ->filterByArray($conditions)
                    ->endUse()
                ->endUse();
        }

        return $abstractProductQuery;
    }

    /**
     * @param LocaleTransfer $localeTransfer
     *
     * @return array
     */
    protected function getLocaleFilterConditionsArray(LocaleTransfer $localeTransfer)
    {
        $conditions = [
            'idLocale' => $localeTransfer->getIdLocale(),
            'localeName' => $localeTransfer->getLocaleName(),
            'isActive' => $localeTransfer->getIsActive(),
        ];
        $conditions = array_filter($conditions, function($value) {
            return $value !== null;
        });

        return $conditions;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery $abstractProductQuery
     * @param \Generated\Shared\Transfer\ProductFeedJoinTransfer $productFeedJoinTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return SpyProductAbstractQuery
     */
    protected function joinProductImages(
        SpyProductAbstractQuery $abstractProductQuery,
        ProductFeedJoinTransfer $productFeedJoinTransfer,
        LocaleTransfer $localeTransfer = null
    ) {
        if ($productFeedJoinTransfer->getIsJoinImage()) {
            $localeTransferConditions = $this->getIdLocaleFilterConditions($localeTransfer);

            $abstractProductQuery = $this->joinProductImageQuery(
                $abstractProductQuery,
                $localeTransferConditions[self::LOCALE_FILTER_VALUE],
                $localeTransferConditions[self::LOCALE_FILTER_CRITERIA]
            );
        }

        return $abstractProductQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery $abstractProductQuery
     * @param $filterValue
     * @param string $filterCriteria
     *
     * @return mixed
     */
    protected function joinProductImageQuery($abstractProductQuery, $filterValue, $filterCriteria)
    {
        $abstractProductQuery
            ->useSpyProductImageSetQuery(null, Criteria::LEFT_JOIN)
                ->filterByFkLocale($filterValue, $filterCriteria)
                    ->useSpyProductImageSetToProductImageQuery(null, Criteria::LEFT_JOIN)
                        ->useSpyProductImageQuery()
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->groupBy(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT);

        $abstractProductQuery = $this->addProductImageSelectedColumns($abstractProductQuery);

        return $abstractProductQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery $abstractProductQuery
     * @param \Generated\Shared\Transfer\ProductFeedJoinTransfer $productFeedJoinTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return SpyProductAbstractQuery
     */
    protected function joinProductCategories(
        SpyProductAbstractQuery $abstractProductQuery,
        ProductFeedJoinTransfer $productFeedJoinTransfer,
        LocaleTransfer $localeTransfer = null
    ) {
        if ($productFeedJoinTransfer->getIsJoinCategory()) {
            $localeTransferConditions = $this->getIdLocaleFilterConditions($localeTransfer);

            $abstractProductQuery
                ->useSpyProductCategoryQuery(null, Criteria::LEFT_JOIN)
                    ->useSpyCategoryQuery(null, Criteria::LEFT_JOIN)
                        ->useAttributeQuery(null, Criteria::LEFT_JOIN)
                            ->filterByFkLocale(
                                $localeTransferConditions[self::LOCALE_FILTER_VALUE],
                                $localeTransferConditions[self::LOCALE_FILTER_CRITERIA]
                            )
                        ->endUse()
                    ->endUse()
                ->endUse();

            $abstractProductQuery = $this->addProductCategorySelectedColumns($abstractProductQuery);
        }

        return $abstractProductQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery $abstractProductQuery
     * @param \Generated\Shared\Transfer\ProductFeedJoinTransfer $productFeedJoinTransfer
     *
     * @return SpyProductAbstractQuery
     */
    protected function joinProductPrices(
        SpyProductAbstractQuery $abstractProductQuery,
        ProductFeedJoinTransfer $productFeedJoinTransfer
    ) {
        if ($productFeedJoinTransfer->getIsJoinPrice()) {
            $abstractProductQuery
                ->usePriceProductQuery(null, Criteria::LEFT_JOIN)
                    ->usePriceTypeQuery(null, Criteria::LEFT_JOIN)
                    ->endUse()
                ->endUse();

            $abstractProductQuery = $this->addProductPriceSelectedColumns($abstractProductQuery);
        }

        return $abstractProductQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery $abstractProductQuery
     * @param \Generated\Shared\Transfer\ProductFeedJoinTransfer $productFeedJoinTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return SpyProductAbstractQuery
     */
    protected function joinProductVariants(
        SpyProductAbstractQuery $abstractProductQuery,
        ProductFeedJoinTransfer $productFeedJoinTransfer,
        LocaleTransfer $localeTransfer = null
    ) {
        if ($productFeedJoinTransfer->getIsJoinVariant()) {
            $localeTransferConditions = $this->getIdLocaleFilterConditions($localeTransfer);

            $abstractProductQuery
                ->useSpyProductQuery(null, Criteria::LEFT_JOIN)
                    ->useSpyProductLocalizedAttributesQuery(null, Criteria::LEFT_JOIN)
                        ->filterByFkLocale(
                            $localeTransferConditions[self::LOCALE_FILTER_VALUE],
                            $localeTransferConditions[self::LOCALE_FILTER_CRITERIA]
                        )
                    ->endUse()
                ->endUse();

            $abstractProductQuery = $this->addVariantSelectedColumns($abstractProductQuery);
        }

        return $abstractProductQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery $abstractProductQuery
     * @param \Generated\Shared\Transfer\ProductFeedJoinTransfer $productFeedJoinTransfer
     *
     * @return SpyProductAbstractQuery
     */
    protected function joinProductOptions(
        SpyProductAbstractQuery $abstractProductQuery,
        ProductFeedJoinTransfer $productFeedJoinTransfer
    ) {
        if ($productFeedJoinTransfer->getIsJoinOption()) {
            $abstractProductQuery
                ->useSpyProductAbstractProductOptionGroupQuery(null, Criteria::LEFT_JOIN)
                    ->useSpyProductOptionGroupQuery(null, Criteria::LEFT_JOIN)
                        ->useSpyProductOptionValueQuery(null, Criteria::LEFT_JOIN)
                        ->endUse()
                    ->endUse()
                ->endUse();

            $abstractProductQuery = $this->addOptionsSelectedColumns($abstractProductQuery);
        }

        return $abstractProductQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery $abstractProductQuery
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return SpyProductAbstractQuery
     */
    protected function joinProductLocalizedAttributes(
        SpyProductAbstractQuery $abstractProductQuery,
        LocaleTransfer $localeTransfer = null
    ) {
        $localeTransferConditions = $this->getIdLocaleFilterConditions($localeTransfer);

        $abstractProductQuery
            ->useSpyProductAbstractLocalizedAttributesQuery(null, Criteria::LEFT_JOIN)
                ->filterByFkLocale(
                    $localeTransferConditions[self::LOCALE_FILTER_VALUE],
                    $localeTransferConditions[self::LOCALE_FILTER_CRITERIA]
                )
            ->endUse();

        $abstractProductQuery = $this->addAbstractProductLocalizedAttributesSelectedColumns($abstractProductQuery);

        return $abstractProductQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery $abstractProductQuery
     *
     * @return SpyProductAbstractQuery
     */
    protected function addProductCategorySelectedColumns($abstractProductQuery)
    {
        $abstractProductQuery
            ->withColumn(SpyCategoryTableMap::COL_IS_ACTIVE, 'CategoryIsActive')
            ->withColumn(SpyCategoryTableMap::COL_IS_CLICKABLE, 'CategoryIsClickable')
            ->withColumn(SpyCategoryTableMap::COL_IS_IN_MENU, 'CategoryIsInMenu')
            ->withColumn(SpyCategoryTableMap::COL_IS_SEARCHABLE, 'CategoryIsSearchable')

            ->withColumn(SpyCategoryAttributeTableMap::COL_FK_LOCALE, 'CategoryFkLocale')
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, 'CategoryName')
            ->withColumn(SpyCategoryAttributeTableMap::COL_META_TITLE, 'CategoryMetaTitle')
            ->withColumn(SpyCategoryAttributeTableMap::COL_META_KEYWORDS, 'CategoryMetaKeywords')
            ->withColumn(SpyCategoryAttributeTableMap::COL_META_DESCRIPTION, 'CategoryMetaDescription')
            ->withColumn(SpyCategoryAttributeTableMap::COL_CATEGORY_IMAGE_NAME, 'CategoryImageName');

        return $abstractProductQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery $abstractProductQuery
     *
     * @return SpyProductAbstractQuery
     */
    protected function addProductImageSelectedColumns($abstractProductQuery)
    {
        $abstractProductQuery
            ->withColumn(SpyProductImageTableMap::COL_EXTERNAL_URL_SMALL, 'ImageExternalUrlSmall')
            ->withColumn(SpyProductImageTableMap::COL_EXTERNAL_URL_LARGE, 'ImageExternalUrlLarge');

        return $abstractProductQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery $abstractProductQuery
     *
     * @return SpyProductAbstractQuery
     */
    protected function addProductPriceSelectedColumns($abstractProductQuery)
    {
        $abstractProductQuery
            ->withColumn(SpyPriceProductTableMap::COL_PRICE, 'PricePrice')
            ->withColumn(SpyPriceProductTableMap::COL_FK_PRODUCT, 'PriceFkProduct')
            ->withColumn(SpyPriceTypeTableMap::COL_NAME, 'PriceTypeName');

        return $abstractProductQuery;
    }

    /**
 * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery $abstractProductQuery
 *
 * @return SpyProductAbstractQuery
 */
    protected function addVariantSelectedColumns($abstractProductQuery)
    {
        $abstractProductQuery
            ->withColumn(SpyProductTableMap::COL_ID_PRODUCT, 'VariantIdProduct')
            ->withColumn(SpyProductTableMap::COL_SKU, 'VariantSku')
            ->withColumn(SpyProductTableMap::COL_IS_ACTIVE, 'VariantIsActive')
            ->withColumn(SpyProductTableMap::COL_ATTRIBUTES, 'VariantAttributes')
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_FK_LOCALE, 'VariantFkLocale')
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_NAME, 'VariantName')
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_ATTRIBUTES, 'VariantAttributes')
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_IS_COMPLETE, 'VariantIsComplete')
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_DESCRIPTION, 'VariantDescription');

        return $abstractProductQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery $abstractProductQuery
     *
     * @return SpyProductAbstractQuery
     */
    protected function addAbstractProductLocalizedAttributesSelectedColumns($abstractProductQuery)
    {
        $abstractProductQuery
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_FK_LOCALE, 'LocalizedFkLocale')
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_NAME, 'LocalizedName')
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_ATTRIBUTES, 'LocalizedAttributes')
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_DESCRIPTION, 'LocalizedDescription')
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_META_TITLE, 'LocalizedMetaTitle')
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_META_KEYWORDS, 'LocalizedMetaKeywords')
            ->withColumn(SpyProductAbstractLocalizedAttributesTableMap::COL_META_DESCRIPTION, 'LocalizedMetaDescription');

        return $abstractProductQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery $abstractProductQuery
     *
     * @return SpyProductAbstractQuery
     */
    protected function addOptionsSelectedColumns($abstractProductQuery)
    {
        $abstractProductQuery
            ->withColumn(SpyProductOptionGroupTableMap::COL_NAME, 'OptionGroupName')
            ->withColumn(SpyProductOptionGroupTableMap::COL_ACTIVE, 'OptionGroupActive')
            ->withColumn(SpyProductOptionValueTableMap::COL_PRICE, 'OptionPrice')
            ->withColumn(SpyProductOptionValueTableMap::COL_SKU, 'OptionSku')
            ->withColumn(SpyProductOptionValueTableMap::COL_VALUE, 'OptionValue');

        return $abstractProductQuery;
    }

}
