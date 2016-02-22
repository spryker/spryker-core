<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductSearch\Persistence\Map\SpyProductSearchTableMap;

class ProductSearchQueryExpander implements ProductSearchQueryExpanderInterface
{

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
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
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $expandableQuery
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function expandProductQuery(ModelCriteria $expandableQuery, LocaleTransfer $locale)
    {
        $expandableQuery->clearSelectColumns();
        $expandableQuery
            ->addJoin(
                SpyTouchTableMap::COL_ITEM_ID,
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                Criteria::LEFT_JOIN
            );

        $this->productQueryContainer
            ->joinProductConcreteCollection($expandableQuery)
            ->joinProductQueryWithLocalizedAttributes($expandableQuery, $locale);

        $expandableQuery->withColumn(SpyProductAbstractTableMap::COL_SKU, 'abstract_sku');
        $expandableQuery->withColumn(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, 'id_product_abstract');
        $this->joinSearchableProducts($expandableQuery);

        return $expandableQuery;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $expandableQuery
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function joinSearchableProducts(ModelCriteria $expandableQuery)
    {
        $expandableQuery->addJoinObject(
            new Join(
                SpyProductTableMap::COL_ID_PRODUCT,
                SpyProductSearchTableMap::COL_FK_PRODUCT,
                Criteria::INNER_JOIN
            ),
            'searchableJoin'
        );
        $expandableQuery->addJoinCondition(
            'searchableJoin',
            SpyProductSearchTableMap::COL_FK_LOCALE . ' = ' .
            SpyLocaleTableMap::COL_ID_LOCALE
        );
        $expandableQuery->addAnd(
            SpyProductSearchTableMap::COL_IS_SEARCHABLE,
            true,
            Criteria::EQUAL
        );

        return $expandableQuery;
    }

}
