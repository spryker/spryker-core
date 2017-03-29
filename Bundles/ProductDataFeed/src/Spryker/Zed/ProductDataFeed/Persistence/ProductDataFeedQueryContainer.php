<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDataFeed\Persistence;

use Generated\Shared\Transfer\ProductDataFeedTransfer;
use Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

/**
 * @method \Spryker\Zed\ProductDataFeed\Persistence\ProductDataFeedPersistenceFactory getFactory()
 */
class ProductDataFeedQueryContainer extends AbstractQueryContainer implements ProductDataFeedQueryContainerInterface
{


    const TOUCH_ITEM_TYPE = 'product_abstract';
    const JOIN_TOUCH_TABLE_CONDITION_NAME = 'JOIN_TOUCH_TABLE_JOIN_NAME';
    const LOCALE_FILTER_VALUE = 'LOCALE_FILTER_VALUE';
    const LOCALE_FILTER_CRITERIA = 'LOCALE_FILTER_CRITERIA';

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
     * @api
     *
     * @param ProductDataFeedTransfer $productDataFeedTransfer
     *
     * @return SpyProductAbstractQuery
     *
     */
    public function getProductDataFeedQuery(ProductDataFeedTransfer $productDataFeedTransfer)
    {
        $abstractProductQuery = $this->productQueryContainer
            ->queryProductAbstract();

        $abstractProductQuery = $this->joinProductLocalizedAttributes(
            $abstractProductQuery,
            $productDataFeedTransfer
        );

        $abstractProductQuery = $this->applyJoins(
            $abstractProductQuery,
            $productDataFeedTransfer
        );
        $abstractProductQuery = $this->applyLocaleFilter($abstractProductQuery, $productDataFeedTransfer);
        $abstractProductQuery = $this->applyDateFilter(
            $abstractProductQuery,
            $productDataFeedTransfer
        );

        return $abstractProductQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery $abstractProductQuery
     * @param ProductDataFeedTransfer $productDataFeedTransfer
     *
     * @return SpyProductAbstractQuery
     */
    protected function applyJoins(
        SpyProductAbstractQuery $abstractProductQuery,
        ProductDataFeedTransfer $productDataFeedTransfer
    ) {
        $abstractProductQuery = $this->joinProductImages($abstractProductQuery, $productDataFeedTransfer);
        $abstractProductQuery = $this->joinProductCategories($abstractProductQuery, $productDataFeedTransfer);
        $abstractProductQuery = $this->joinProductPrices($abstractProductQuery, $productDataFeedTransfer);
        $abstractProductQuery = $this->joinProductVariants($abstractProductQuery, $productDataFeedTransfer);
        $abstractProductQuery = $this->joinProductOptions($abstractProductQuery, $productDataFeedTransfer);

        return $abstractProductQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery $abstractProductQuery
     * @param ProductDataFeedTransfer $productDataFeedTransfer
     *
     * @return SpyProductAbstractQuery
     *
     */
    protected function applyLocaleFilter(
        SpyProductAbstractQuery $abstractProductQuery,
        ProductDataFeedTransfer $productDataFeedTransfer
    ) {
        if ($productDataFeedTransfer->getLocaleId() !== null) {
            $abstractProductQuery
                ->useSpyProductAbstractLocalizedAttributesQuery()
                ->filterByFkLocale($productDataFeedTransfer->getLocaleId())
                ->endUse();
        }

        return $abstractProductQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery $abstractProductQuery
     * @param ProductDataFeedTransfer $productDataFeedTransfer
     *
     * @return SpyProductAbstractQuery
     */
    protected function joinProductImages(
        SpyProductAbstractQuery $abstractProductQuery,
        ProductDataFeedTransfer $productDataFeedTransfer
    ) {
        if ($productDataFeedTransfer->getIsJoinImage()) {
            $localeTransferConditions = $this->getIdLocaleFilterConditions($productDataFeedTransfer->getLocaleId());

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
        }

        return $abstractProductQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery $abstractProductQuery
     * @param ProductDataFeedTransfer $productDataFeedTransfer
     *
     * @return SpyProductAbstractQuery
     *
     */
    protected function joinProductCategories(
        SpyProductAbstractQuery $abstractProductQuery,
        ProductDataFeedTransfer $productDataFeedTransfer
    ) {
        if ($productDataFeedTransfer->getIsJoinCategory()) {
            $localeTransferConditions = $this->getIdLocaleFilterConditions($productDataFeedTransfer->getLocaleId());

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
        }

        return $abstractProductQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery $abstractProductQuery
     * @param ProductDataFeedTransfer $productDataFeedTransfer
     *
     * @return SpyProductAbstractQuery
     *
     */
    protected function joinProductPrices(
        SpyProductAbstractQuery $abstractProductQuery,
        ProductDataFeedTransfer $productDataFeedTransfer
    ) {
        if ($productDataFeedTransfer->getIsJoinPrice()) {
            $abstractProductQuery
                ->usePriceProductQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinPriceType()
                ->endUse();
        }

        return $abstractProductQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery $abstractProductQuery
     * @param ProductDataFeedTransfer $productDataFeedTransfer
     *
     * @return SpyProductAbstractQuery
     *
     */
    protected function joinProductVariants(
        SpyProductAbstractQuery $abstractProductQuery,
        ProductDataFeedTransfer $productDataFeedTransfer
    ) {
        if ($productDataFeedTransfer->getIsJoinVariant()) {
            $localeTransferConditions = $this->getIdLocaleFilterConditions($productDataFeedTransfer->getLocaleId());

            $abstractProductQuery
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
     * @param ProductDataFeedTransfer $productDataFeedTransfer
     *
     * @return SpyProductAbstractQuery
     *
     */
    protected function joinProductOptions(
        SpyProductAbstractQuery $abstractProductQuery,
        ProductDataFeedTransfer $productDataFeedTransfer
    ) {
        if ($productDataFeedTransfer->getIsJoinOption()) {
            $abstractProductQuery
                ->useSpyProductAbstractProductOptionGroupQuery(null, Criteria::LEFT_JOIN)
                ->useSpyProductOptionGroupQuery(null, Criteria::LEFT_JOIN)
                ->leftJoinSpyProductOptionValue()
                ->endUse()
                ->endUse();
        }

        return $abstractProductQuery;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery $abstractProductQuery
     * @param ProductDataFeedTransfer $productDataFeedTransfer
     *
     * @return SpyProductAbstractQuery
     *
     */
    protected function joinProductLocalizedAttributes(
        SpyProductAbstractQuery $abstractProductQuery,
        ProductDataFeedTransfer $productDataFeedTransfer
    ) {
        $localeTransferConditions = $this->getIdLocaleFilterConditions($productDataFeedTransfer->getLocaleId());

        $abstractProductQuery
            ->useSpyProductAbstractLocalizedAttributesQuery(null, Criteria::LEFT_JOIN)
            ->filterByFkLocale(
                $localeTransferConditions[self::LOCALE_FILTER_VALUE],
                $localeTransferConditions[self::LOCALE_FILTER_CRITERIA]
            )
            ->endUse();

        return $abstractProductQuery;
    }

    /**
     * @param integer $localeId
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
     * @param SpyProductAbstractQuery $abstractProductQuery
     *
     * @return SpyProductAbstractQuery
     *
     */
    protected function joinTouchTable(SpyProductAbstractQuery $abstractProductQuery)
    {
        $abstractProductQuery->addJoin(
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
            SpyTouchTableMap::COL_ITEM_ID,
            Criteria::INNER_JOIN
        );
        $abstractProductQuery->condition(
            self::JOIN_TOUCH_TABLE_CONDITION_NAME,
            SpyTouchTableMap::COL_ITEM_TYPE . ' = ?',
            self::TOUCH_ITEM_TYPE,
            \PDO::PARAM_STR
        );
        $abstractProductQuery->where([self::JOIN_TOUCH_TABLE_CONDITION_NAME]);

        return $abstractProductQuery;
    }

    /**
     * @param SpyProductAbstractQuery $entityQuery
     * @param ProductDataFeedTransfer|null $productDataFeedTransfer
     *
     * @return SpyProductAbstractQuery
     */
    protected function applyDateFilter(
        SpyProductAbstractQuery $entityQuery,
        ProductDataFeedTransfer $productDataFeedTransfer = null
    ) {
        if ($productDataFeedTransfer !== null) {
            $entityQuery = $this->joinTouchTable($entityQuery);

            if ($productDataFeedTransfer->getUpdatedFrom() !== null) {
                $entityQuery->condition(
                    'updatedFromCondition',
                    SpyTouchTableMap::COL_TOUCHED . '> ?',
                    $productDataFeedTransfer->getUpdatedFrom(),
                    \PDO::PARAM_STR
                );
                $entityQuery->where(['updatedFromCondition']);
            }

            if ($productDataFeedTransfer->getUpdatedTo() !== null) {
                $entityQuery->condition(
                    'updatedToCondition',
                    SpyTouchTableMap::COL_TOUCHED . '< ?',
                    $productDataFeedTransfer->getUpdatedTo(),
                    \PDO::PARAM_STR
                );
                $entityQuery->where(['updatedToCondition']);
            }
        }

        return $entityQuery;
    }

}
