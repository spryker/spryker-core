<?php

namespace SprykerFeature\Zed\ProductFrontendExporterConnector\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use SprykerEngine\Shared\Locale\Dto\LocaleDto;
use SprykerEngine\Zed\Touch\Persistence\Propel\Map\SpyTouchTableMap;
use SprykerFeature\Zed\Product\Persistence\ProductQueryContainerInterface;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap;
use Propel\Runtime\ActiveQuery\ModelCriteria;

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
     * @param LocaleDto $locale
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, LocaleDto $locale)
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

        $expandableQuery->withColumn(SpyAbstractProductTableMap::COL_SKU, 'sku');

        return $expandableQuery;
    }
}
