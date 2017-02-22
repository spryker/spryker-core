<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataFeed\Persistence\QueryBuilder;

use Generated\Shared\Transfer\DataFeedConditionTransfer;
use Generated\Shared\Transfer\DataFeedPaginationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductFeedJoinsTransfer;
use Generated\Shared\Transfer\ProductFeedJoinTransfer;
use Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductQueryBuilder implements QueryBuilderInterface
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

        return $abstractProductQuery;
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
        //todo: implement joins / split method

        $abstractProductQuery->useSpyProductAbstractLocalizedAttributesQuery()
                ->filterByFkLocale($localeTransfer->getIdLocale())
            ->endUse();

        //image
        if ($productFeedJoinTransfer->getIsJoinImage()) {
            $abstractProductQuery
                ->useSpyProductImageSetQuery()
                    ->filterByFkLocale($localeTransfer->getIdLocale())
                    ->useSpyProductImageSetToProductImageQuery()
                    ->endUse()
                ->endUse();
        }

        //category
        if ($productFeedJoinTransfer->getIsJoinCategory()) {
            $abstractProductQuery
                ->useSpyProductCategoryQuery()
                    ->useSpyCategoryQuery()
                        ->useAttributeQuery()
                            ->filterByFkLocale($localeTransfer->getIdLocale())
                        ->endUse()
                    ->endUse()
                    ->useCategoryQuery()
                    ->endUse()
                ->endUse();
        }

        //price
        if ($productFeedJoinTransfer->getIsJoinPrice()) {
            $abstractProductQuery
                ->usePriceProductQuery()
                    ->usePriceTypeQuery()
                    ->endUse()
                ->endUse();
        }

        //variants
        if ($productFeedJoinTransfer->getIsJoinVariant()) {
            $abstractProductQuery
                ->useSpyProductQuery()
                    ->useSpyProductLocalizedAttributesQuery()
                        ->filterByFkLocale($localeTransfer->getIdLocale())
                    ->endUse()
                ->useSpyCategoryAttributeQuery()
                    ->filterByFkLocale($localeTransfer->getIdLocale())
                ->endUse();
        }
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
     * @param DataFeedPaginationTransfer $dataFeedPaginationTransfer
     *
     * @return void
     */
    protected function applyPagination(
        SpyProductAbstractQuery $abstractProductQuery,
        DataFeedPaginationTransfer $dataFeedPaginationTransfer
    ) {
        $this->setQueryLimit($abstractProductQuery, $dataFeedPaginationTransfer);
        $this->setQueryOffset($abstractProductQuery, $dataFeedPaginationTransfer);
    }

    /**
     * @param SpyProductAbstractQuery $abstractProductQuery
     * @param DataFeedPaginationTransfer $dataFeedPaginationTransfer
     *
     * @return void
     */
    protected function setQueryLimit(
        SpyProductAbstractQuery $abstractProductQuery,
        DataFeedPaginationTransfer $dataFeedPaginationTransfer
    ) {
        if ($dataFeedPaginationTransfer->getLimit()) {
            $abstractProductQuery
                ->setLimit($dataFeedPaginationTransfer->getLimit());
        }
    }

    /**
     * @param SpyProductAbstractQuery $abstractProductQuery
     * @param DataFeedPaginationTransfer $dataFeedPaginationTransfer
     *
     * @return void
     */
    protected function setQueryOffset(
        SpyProductAbstractQuery $abstractProductQuery,
        DataFeedPaginationTransfer $dataFeedPaginationTransfer
    ) {
        if ($dataFeedPaginationTransfer->getOffset()) {
            $abstractProductQuery
                ->setOffset($dataFeedPaginationTransfer->getOffset());
        }
    }

}
