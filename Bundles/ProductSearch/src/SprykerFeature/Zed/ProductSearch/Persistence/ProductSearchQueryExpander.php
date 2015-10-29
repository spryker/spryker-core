<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductSearch\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Exception\PropelException;
use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use SprykerFeature\Zed\Product\Persistence\ProductQueryContainerInterface;
use Orm\Zed\Product\Persistence\Map\SpyAbstractProductTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductSearch\Persistence\Map\SpySearchableProductsTableMap;

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
                SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT,
                Criteria::LEFT_JOIN
            );

        $this->productQueryContainer
            ->joinConcreteProducts($expandableQuery)
            ->joinProductQueryWithLocalizedAttributes($expandableQuery, $locale)
        ;

        $expandableQuery->withColumn(SpyAbstractProductTableMap::COL_SKU, 'abstract_sku');
        $expandableQuery->withColumn(SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT, 'id_abstract_product');
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
                SpySearchableProductsTableMap::COL_FK_PRODUCT,
                Criteria::INNER_JOIN
            ),
            'searchableJoin'
        );
        $expandableQuery->addJoinCondition(
            'searchableJoin',
            SpySearchableProductsTableMap::COL_FK_LOCALE . ' = ' .
            SpyLocaleTableMap::COL_ID_LOCALE
        );
        $expandableQuery->addAnd(
            SpySearchableProductsTableMap::COL_IS_SEARCHABLE,
            true,
            Criteria::EQUAL
        );

        return $expandableQuery;
    }

}
