<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductSearch\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Exception\PropelException;
use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductSearch\Persistence\Map\SpyProductSearchTableMap;

class ProductSearchQueryExpander implements ProductSearchQueryExpanderInterface
{

    /**
     * @var ProductQueryContainerInterface
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
     * @param ModelCriteria $expandableQuery
     * @param LocaleTransfer $locale
     *
     * @return ModelCriteria
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
            ->joinConcreteProducts($expandableQuery)
            ->joinProductQueryWithLocalizedAttributes($expandableQuery, $locale);

        $expandableQuery->withColumn(SpyProductAbstractTableMap::COL_SKU, 'abstract_sku');
        $expandableQuery->withColumn(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, 'id_product_abstract');
        $this->joinSearchableProducts($expandableQuery);

        return $expandableQuery;
    }

    /**
     * @param ModelCriteria $expandableQuery
     *
     * @throws PropelException
     *
     * @return ModelCriteria
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
