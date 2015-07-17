<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductFrontendExporterConnector\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Touch\Persistence\Propel\Map\SpyTouchTableMap;
use SprykerFeature\Zed\Product\Persistence\ProductQueryContainerInterface;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap;

class ProductQueryExpander implements ProductQueryExpanderInterface
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
    public function expandQuery(ModelCriteria $expandableQuery, LocaleTransfer $locale)
    {
        $expandableQuery->clearSelectColumns();

        $expandableQuery->addJoin(
            SpyTouchTableMap::COL_ITEM_ID,
            SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT,
            Criteria::INNER_JOIN
        );

        $this->productQueryContainer
            ->joinConcreteProducts($expandableQuery)
            ->joinProductQueryWithLocalizedAttributes($expandableQuery, $locale)
        ;

        $expandableQuery->withColumn(SpyAbstractProductTableMap::COL_SKU, 'abstract_sku');
        $expandableQuery->withColumn(SpyAbstractProductTableMap::COL_ATTRIBUTES, 'abstract_attributes');
        $expandableQuery->withColumn(SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT, 'id_abstract_product');
        $expandableQuery->groupBy('abstract_sku');

        return $expandableQuery;
    }

}
