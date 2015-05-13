<?php

namespace SprykerFeature\Zed\ProductSearch\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Locale\Persistence\Propel\Map\SpyLocaleTableMap;
use SprykerEngine\Zed\Touch\Persistence\Propel\Map\SpyTouchTableMap;
use SprykerFeature\Zed\Product\Persistence\ProductQueryContainerInterface;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyProductTableMap;
use SprykerFeature\Zed\ProductSearch\Persistence\Propel\Map\SpySearchableProductsTableMap;

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
                SpyProductTableMap::COL_ID_PRODUCT,
                Criteria::LEFT_JOIN
            );
        $expandableQuery->addAnd(
            SpyProductTableMap::COL_IS_ACTIVE,
            true,
            Criteria::EQUAL
        );
        $expandableQuery->addAnd(
            SpyLocaleTableMap::COL_LOCALE_NAME,
            $locale->getLocaleName(),
            Criteria::EQUAL
        );
        $expandableQuery->addAnd(
            SpyLocaleTableMap::COL_IS_ACTIVE,
            true,
            Criteria::EQUAL
        );

        $expandableQuery = $this->productQueryContainer->joinProductQueryWithLocalizedAttributes($expandableQuery, $locale);

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
