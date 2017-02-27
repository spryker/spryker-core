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
use Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery;
use Orm\Zed\ProductImage\Persistence\Base\SpyProductImage;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetTableMap;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductQueryBuilder extends QueryBuilderAbstract implements QueryBuilderInterface
{

    /**
     * @param ProductQueryContainerInterface $productQueryContainer
     */
    protected $productQueryContainer;

    /**
     * @param ProductQueryContainerInterface $productQueryContainer
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
        $localeTransfer = $dataFeedConditionTransfer->getLocale();
        $abstractProductQuery = $this->productQueryContainer
            ->queryProductAbstract();

        $this->applyJoins($abstractProductQuery, $productFeedJoinTransfer, $localeTransfer);
        $this->applyLocaleFilter($abstractProductQuery, $dataFeedConditionTransfer->getLocale());
        $this->applyPagination($abstractProductQuery, $dataFeedConditionTransfer->getPagination());
        $this->applyDateFilter($abstractProductQuery, $dataFeedConditionTransfer->getDateFilter());

        return $abstractProductQuery;
    }

    /**
     * @param SpyProductAbstractQuery $abstractProductQuery
     * @param DataFeedDateFilterTransfer $dataFeedDateFilterTransfer
     *
     * @return void
     */
    protected function applyDateFilter(
        SpyProductAbstractQuery $abstractProductQuery,
        DataFeedDateFilterTransfer $dataFeedDateFilterTransfer
    ) {
        //todo: implement
    }

    /**
     * @param SpyProductAbstractQuery $abstractProductQuery
     * @param ProductFeedJoinTransfer $productFeedJoinTransfer
     * @param LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function applyJoins(
        SpyProductAbstractQuery $abstractProductQuery,
        ProductFeedJoinTransfer $productFeedJoinTransfer,
        LocaleTransfer $localeTransfer
    ) {
//        $this->joinProductLocalizedAttributes($abstractProductQuery, $localeTransfer);
        $this->joinProductImage($abstractProductQuery, $productFeedJoinTransfer, $localeTransfer);
//        $this->joinProductCategory($abstractProductQuery, $productFeedJoinTransfer, $localeTransfer);
//        $this->joinProductPrice($abstractProductQuery, $productFeedJoinTransfer);
//        $this->joinProductVariant($abstractProductQuery, $productFeedJoinTransfer, $localeTransfer);
    }

    /**
     * @param SpyProductAbstractQuery $abstractProductQuery
     * @param LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function applyLocaleFilter(
        SpyProductAbstractQuery $abstractProductQuery,
        LocaleTransfer $localeTransfer
    ) {
        //todo: implement filter by locale if it's necessary;
    }

    /**
     * @param SpyProductAbstractQuery $abstractProductQuery
     * @param ProductFeedJoinTransfer $productFeedJoinTransfer
     * @param LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function joinProductImage(
        SpyProductAbstractQuery $abstractProductQuery,
        ProductFeedJoinTransfer $productFeedJoinTransfer,
        LocaleTransfer $localeTransfer
    ) {
        if ($productFeedJoinTransfer->getIsJoinImage()) {
//            $abstractProductQuery
//                ->useSpyProductImageSetQuery();
//                ->useSpyProductImageSetQuery('SpyProductImageSet', Criteria::LEFT_JOIN)
//                    ->filterByFkLocale($localeTransfer->getIdLocale())
//                    ->useSpyProductImageSetToProductImageQuery('SpyProductImage', Criteria::LEFT_JOIN)
//                    ->endUse()
//                ->endUse();
        }
    }

    /**
     * @param SpyProductAbstractQuery $abstractProductQuery
     * @param ProductFeedJoinTransfer $productFeedJoinTransfer
     * @param LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function joinProductCategory(
        SpyProductAbstractQuery $abstractProductQuery,
        ProductFeedJoinTransfer $productFeedJoinTransfer,
        LocaleTransfer $localeTransfer
    ) {
        if ($productFeedJoinTransfer->getIsJoinCategory()) {
            $abstractProductQuery
                ->useSpyProductCategoryQuery(null, Criteria::LEFT_JOIN)
                    ->useSpyCategoryQuery(null, Criteria::LEFT_JOIN)
                        ->useAttributeQuery(null, Criteria::LEFT_JOIN)
                            ->filterByFkLocale($localeTransfer->getIdLocale())
                        ->endUse()
                    ->endUse()
                ->endUse();
        }
    }

    /**
     * @param SpyProductAbstractQuery $abstractProductQuery
     * @param ProductFeedJoinTransfer $productFeedJoinTransfer
     *
     * @return void
     */
    protected function joinProductPrice(
        SpyProductAbstractQuery $abstractProductQuery,
        ProductFeedJoinTransfer $productFeedJoinTransfer
    ) {
        if ($productFeedJoinTransfer->getIsJoinPrice()) {
            $abstractProductQuery
                ->usePriceProductQuery(null, Criteria::LEFT_JOIN)
                    ->usePriceTypeQuery(null, Criteria::LEFT_JOIN)
                    ->endUse()
                ->endUse();
        }
    }

    /**
     * @param SpyProductAbstractQuery $abstractProductQuery
     * @param ProductFeedJoinTransfer $productFeedJoinTransfer
     * @param LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function joinProductVariant(
        SpyProductAbstractQuery $abstractProductQuery,
        ProductFeedJoinTransfer $productFeedJoinTransfer,
        LocaleTransfer $localeTransfer
    ) {
        if ($productFeedJoinTransfer->getIsJoinVariant()) {
            $abstractProductQuery
                ->useSpyProductQuery('useSpyProductQuery', Criteria::LEFT_JOIN)
                    ->useSpyProductLocalizedAttributesQuery(null, Criteria::LEFT_JOIN)
                        ->filterByFkLocale($localeTransfer->getIdLocale())
                    ->endUse()
                ->endUse();
        }
    }

    /**
     * @param SpyProductAbstractQuery $abstractProductQuery
     * @param LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function joinProductLocalizedAttributes(
        SpyProductAbstractQuery $abstractProductQuery,
        LocaleTransfer $localeTransfer
    ) {
        $abstractProductQuery
            ->useSpyProductAbstractLocalizedAttributesQuery(null, Criteria::LEFT_JOIN)
                ->filterByFkLocale($localeTransfer->getIdLocale())
            ->endUse();
    }

}
