<?php

namespace SprykerFeature\Zed\ProductFrontendExporterPriceConnector\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Price\Persistence\Propel\Map\SpyPriceProductTableMap;
use SprykerFeature\Zed\Price\Persistence\Propel\Map\SpyPriceTypeTableMap;
use SprykerFeature\Zed\Price\Persistence\Propel\SpyPriceTypeQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyProductTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\Join;

class ProductFrontendExporterPriceConnectorQueryContainer extends AbstractQueryContainer
{
    /**
     * @param ModelCriteria $expandableQuery
     * @param int $idPriceType
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, $idPriceType)
    {
        $expandableQuery->addJoin(
            SpyProductTableMap::COL_ID_PRODUCT,
            SpyPriceProductTableMap::COL_FK_PRODUCT,
            Criteria::LEFT_JOIN
        );

        $expandableQuery
            ->addJoinObject(
                (new Join(
                    SpyPriceProductTableMap::COL_FK_PRICE_TYPE,
                    SpyPriceTypeTableMap::COL_ID_PRICE_TYPE,
                    Criteria::LEFT_JOIN
                ))->setRightTableAlias('spy_price_type')
            );

        $equalPriceType = $expandableQuery->getNewCriterion(
            SpyPriceProductTableMap::COL_FK_PRICE_TYPE,
            $idPriceType,
            Criteria::EQUAL
        );

        $expandableQuery->add($equalPriceType);

        $expandableQuery->withColumn(
            'GROUP_CONCAT(spy_price_product.price)',
            'prices'
        );
        $expandableQuery->withColumn(
            'GROUP_CONCAT(spy_price_type.name)',
            'price_types'
        );

        $expandableQuery->groupBy('sku');

        return $expandableQuery;
    }

    /**
     * @param $priceType
     * @return $this|SpyPriceTypeQuery
     */
    public function getFkDefaultPriceType($priceType)
    {
        return SpyPriceTypeQuery::create()
            ->filterByName($priceType);
    }
}
