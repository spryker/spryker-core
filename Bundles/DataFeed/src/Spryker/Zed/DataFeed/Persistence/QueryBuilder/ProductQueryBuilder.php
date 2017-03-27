<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataFeed\Persistence\QueryBuilder;

use Generated\Shared\Transfer\DataFeedConditionTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductFeedJoinTransfer;
use Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductQueryBuilder extends QueryBuilderAbstract implements QueryBuilderInterface
{

    const TOUCH_ITEM_TYPE = 'product_abstract';

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
        $abstractProductQuery = $this->applyDateFilter(
            $abstractProductQuery,
            $dataFeedConditionTransfer->getDateFilter(),
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
            self::TOUCH_ITEM_TYPE
        );

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
            ->leftJoinWithSpyProductImageSet()
            ->useSpyProductImageSetQuery(null, Criteria::LEFT_JOIN)
                ->filterByFkLocale($filterValue, $filterCriteria)
                    ->joinWithSpyProductImageSetToProductImage()
                    ->useSpyProductImageSetToProductImageQuery(null, Criteria::LEFT_JOIN)
                        ->leftJoinWithSpyProductImage()
                    ->endUse()
                ->endUse();

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
                ->joinWithSpyProductCategory()
                ->useSpyProductCategoryQuery(null, Criteria::LEFT_JOIN)
                    ->joinWithSpyCategory()
                    ->useSpyCategoryQuery(null, Criteria::LEFT_JOIN)
                        ->joinWithAttribute()
                        ->useAttributeQuery(null, Criteria::LEFT_JOIN)
                            ->filterByFkLocale(
                                $localeTransferConditions[self::LOCALE_FILTER_VALUE],
                                $localeTransferConditions[self::LOCALE_FILTER_CRITERIA]
                            )
                        ->endUse()
                    ->endUse()
                ->endUse();
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
                ->joinWithPriceProduct()
                ->usePriceProductQuery(null, Criteria::LEFT_JOIN)
                    ->joinWithPriceType()
                    ->usePriceTypeQuery(null, Criteria::LEFT_JOIN)
                    ->endUse()
                ->endUse();
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
                ->joinWithSpyProduct()
                ->useSpyProductQuery(null, Criteria::LEFT_JOIN)
                    ->useSpyProductLocalizedAttributesQuery(null, Criteria::LEFT_JOIN)
                        ->filterByFkLocale(
                            $localeTransferConditions[self::LOCALE_FILTER_VALUE],
                            $localeTransferConditions[self::LOCALE_FILTER_CRITERIA]
                        )
                    ->endUse()
                ->endUse();
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
                ->joinWithSpyProductAbstractProductOptionGroup()
                ->useSpyProductAbstractProductOptionGroupQuery(null, Criteria::LEFT_JOIN)
                    ->joinWithSpyProductOptionGroup()
                    ->useSpyProductOptionGroupQuery(null, Criteria::LEFT_JOIN)
                        ->joinWithSpyProductOptionValue()
                        ->useSpyProductOptionValueQuery(null, Criteria::LEFT_JOIN)
                        ->endUse()
                    ->endUse()
                ->endUse();
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

        $abstractProductQuery->leftJoinWithSpyProductAbstractLocalizedAttributes()
            ->useSpyProductAbstractLocalizedAttributesQuery(null, Criteria::LEFT_JOIN)
                ->filterByFkLocale(
                    $localeTransferConditions[self::LOCALE_FILTER_VALUE],
                    $localeTransferConditions[self::LOCALE_FILTER_CRITERIA]
                )
            ->endUse();

        return $abstractProductQuery;
    }

}
