<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAbstractDataFeed\Persistence;

use Generated\Shared\Transfer\ProductAbstractDataFeedTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Stock\Persistence\Map\SpyStockProductTableMap;
use Propel\Runtime\ActiveQuery\Criteria;

class ProductAbstractJoinQuery implements ProductAbstractJoinQueryInterface
{
    public const LOCALE_FILTER_VALUE = 'LOCALE_FILTER_VALUE';
    public const LOCALE_FILTER_CRITERIA = 'LOCALE_FILTER_CRITERIA';

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $abstractProductQuery
     * @param \Generated\Shared\Transfer\ProductAbstractDataFeedTransfer $abstractProductDataFeedTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function applyJoins(
        SpyProductAbstractQuery $abstractProductQuery,
        ProductAbstractDataFeedTransfer $abstractProductDataFeedTransfer
    ) {
        $abstractProductQuery = $this->joinProductLocalizedAttributes($abstractProductQuery, $abstractProductDataFeedTransfer);
        $abstractProductQuery = $this->joinConcreteProducts($abstractProductQuery, $abstractProductDataFeedTransfer);
        $abstractProductQuery = $this->joinProductImages($abstractProductQuery, $abstractProductDataFeedTransfer);
        $abstractProductQuery = $this->joinProductCategories($abstractProductQuery, $abstractProductDataFeedTransfer);
        $abstractProductQuery = $this->joinProductPrices($abstractProductQuery, $abstractProductDataFeedTransfer);
        $abstractProductQuery = $this->joinProductOptions($abstractProductQuery, $abstractProductDataFeedTransfer);

        return $abstractProductQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $abstractProductQuery
     * @param \Generated\Shared\Transfer\ProductAbstractDataFeedTransfer $abstractProductDataFeedTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function joinProductImages(
        SpyProductAbstractQuery $abstractProductQuery,
        ProductAbstractDataFeedTransfer $abstractProductDataFeedTransfer
    ) {
        if (!$abstractProductDataFeedTransfer->getJoinImage()) {
            return $abstractProductQuery;
        }
        $localeTransferConditions = $this->getIdLocaleFilterConditions($abstractProductDataFeedTransfer->getIdLocale());

        $abstractProductQuery
            ->useSpyProductImageSetQuery(null, Criteria::LEFT_JOIN)
                ->filterByFkLocale(
                    $localeTransferConditions[self::LOCALE_FILTER_VALUE],
                    $localeTransferConditions[self::LOCALE_FILTER_CRITERIA]
                )
                ->useSpyProductImageSetToProductImageQuery(null, Criteria::LEFT_JOIN)
                    ->leftJoinSpyProductImage()
                ->endUse()
            ->endUse();

        return $abstractProductQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $abstractProductQuery
     * @param \Generated\Shared\Transfer\ProductAbstractDataFeedTransfer $abstractProductDataFeedTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function joinProductCategories(
        SpyProductAbstractQuery $abstractProductQuery,
        ProductAbstractDataFeedTransfer $abstractProductDataFeedTransfer
    ) {
        if (!$abstractProductDataFeedTransfer->getJoinCategory()) {
            return $abstractProductQuery;
        }
        $localeTransferConditions = $this->getIdLocaleFilterConditions($abstractProductDataFeedTransfer->getIdLocale());

        $abstractProductQuery
            ->useSpyProductCategoryQuery(null, Criteria::LEFT_JOIN)
                ->useSpyCategoryQuery(null, Criteria::LEFT_JOIN)
                    ->innerJoinNode()
                    ->useAttributeQuery(null, Criteria::LEFT_JOIN)
                        ->filterByFkLocale(
                            $localeTransferConditions[self::LOCALE_FILTER_VALUE],
                            $localeTransferConditions[self::LOCALE_FILTER_CRITERIA]
                        )
                    ->endUse()
                ->endUse()
            ->endUse();

        return $abstractProductQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $abstractProductQuery
     * @param \Generated\Shared\Transfer\ProductAbstractDataFeedTransfer $abstractProductDataFeedTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function joinProductPrices(
        SpyProductAbstractQuery $abstractProductQuery,
        ProductAbstractDataFeedTransfer $abstractProductDataFeedTransfer
    ) {
        if (!$abstractProductDataFeedTransfer->getJoinPrice()) {
            return $abstractProductQuery;
        }
        $abstractProductQuery
            ->usePriceProductQuery(null, Criteria::LEFT_JOIN)
                ->joinPriceType()
            ->endUse();

        return $abstractProductQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $abstractProductQuery
     * @param \Generated\Shared\Transfer\ProductAbstractDataFeedTransfer $abstractProductDataFeedTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function joinConcreteProducts(
        SpyProductAbstractQuery $abstractProductQuery,
        ProductAbstractDataFeedTransfer $abstractProductDataFeedTransfer
    ) {
        if (!$abstractProductDataFeedTransfer->getJoinProduct()) {
            return $abstractProductQuery;
        }
        $localeTransferConditions = $this->getIdLocaleFilterConditions($abstractProductDataFeedTransfer->getIdLocale());

        $abstractProductQuery
            ->useSpyProductQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinStockProduct()
                ->useSpyProductLocalizedAttributesQuery(null, Criteria::LEFT_JOIN)
                    ->filterByFkLocale(
                        $localeTransferConditions[self::LOCALE_FILTER_VALUE],
                        $localeTransferConditions[self::LOCALE_FILTER_CRITERIA]
                    )
                ->endUse()
                ->useSpyProductImageSetQuery()
                    ->useSpyProductImageSetToProductImageQuery()
                        ->leftJoinSpyProductImage()
                    ->endUse()
                ->endUse()
            ->endUse();

        $abstractProductQuery->groupBy(SpyStockProductTableMap::COL_FK_STOCK);

        return $abstractProductQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $abstractProductQuery
     * @param \Generated\Shared\Transfer\ProductAbstractDataFeedTransfer $abstractProductDataFeedTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function joinProductOptions(
        SpyProductAbstractQuery $abstractProductQuery,
        ProductAbstractDataFeedTransfer $abstractProductDataFeedTransfer
    ) {
        if (!$abstractProductDataFeedTransfer->getJoinOption()) {
            return $abstractProductQuery;
        }
        $abstractProductQuery
            ->useSpyProductAbstractProductOptionGroupQuery(null, Criteria::LEFT_JOIN)
                ->useSpyProductOptionGroupQuery(null, Criteria::LEFT_JOIN)
                    ->leftJoinSpyProductOptionValue()
                ->endUse()
            ->endUse();

        return $abstractProductQuery;
    }

    /**
     * @param int|null $localeId
     *
     * @return array
     */
    protected function getIdLocaleFilterConditions($localeId = null)
    {
        if ($localeId !== null) {
            $filterCriteria = Criteria::EQUAL;
            $filterValue = $localeId;
        } else {
            $filterCriteria = Criteria::NOT_EQUAL;
            $filterValue = null;
        }

        return [
            self::LOCALE_FILTER_VALUE => $filterValue,
            self::LOCALE_FILTER_CRITERIA => $filterCriteria,
        ];
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $abstractProductQuery
     * @param \Generated\Shared\Transfer\ProductAbstractDataFeedTransfer $abstractProductDataFeedTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function joinProductLocalizedAttributes(
        SpyProductAbstractQuery $abstractProductQuery,
        ProductAbstractDataFeedTransfer $abstractProductDataFeedTransfer
    ) {
        $localeTransferConditions = $this->getIdLocaleFilterConditions($abstractProductDataFeedTransfer->getIdLocale());

        $abstractProductQuery
            ->useSpyProductAbstractLocalizedAttributesQuery(null, Criteria::LEFT_JOIN)
                ->filterByFkLocale(
                    $localeTransferConditions[self::LOCALE_FILTER_VALUE],
                    $localeTransferConditions[self::LOCALE_FILTER_CRITERIA]
                )
            ->endUse();

        return $abstractProductQuery;
    }
}
