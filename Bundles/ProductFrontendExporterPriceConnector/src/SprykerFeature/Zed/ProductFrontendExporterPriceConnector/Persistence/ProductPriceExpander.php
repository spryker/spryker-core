<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductFrontendExporterPriceConnector\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerFeature\Zed\Price\Persistence\Propel\Map\SpyPriceProductTableMap;
use SprykerFeature\Zed\Price\Persistence\Propel\Map\SpyPriceTypeTableMap;
use SprykerFeature\Zed\Product\Persistence\ProductQueryContainerInterface;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyProductTableMap;

class ProductPriceExpander implements ProductPriceExpanderInterface
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
     * @param int $idPriceType
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, $idPriceType)
    {
        $this->productQueryContainer->joinConcreteProducts($expandableQuery);

        $expandableQuery->addJoinObject(
            (new Join(
                SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT,
                SpyPriceProductTableMap::COL_FK_ABSTRACT_PRODUCT,
                Criteria::LEFT_JOIN
            ))->setRightTableAlias('abstract_price_table')
        );

        $expandableQuery->addJoinObject(
            (new Join(
                SpyProductTableMap::COL_ID_PRODUCT,
                SpyPriceProductTableMap::COL_FK_PRODUCT,
                Criteria::LEFT_JOIN
            ))->setRightTableAlias('concrete_price_table'),
            'concretePriceJoin'
        );

        $expandableQuery->addJoinCondition(
            'concretePriceJoin',
            'concrete_price_table.fk_price_type',
            $idPriceType,
            Criteria::EQUAL
        );

        $expandableQuery
            ->addJoinObject(
                (new Join(
                    'concrete_price_table.fk_price_type',
                    SpyPriceTypeTableMap::COL_ID_PRICE_TYPE,
                    Criteria::LEFT_JOIN
                ))->setRightTableAlias('spy_price_type')
            );

        $expandableQuery->withColumn(
            'abstract_price_table.price',
            'abstract_price'
        );

        $expandableQuery->withColumn(
            'GROUP_CONCAT(concrete_price_table.price)',
            'concrete_prices'
        );

        $expandableQuery->withColumn(
            'GROUP_CONCAT(spy_price_type.name)',
            'price_types'
        );

        $expandableQuery->groupBy('abstract_sku');

        return $expandableQuery;
    }

}
