<?php

namespace SprykerFeature\Zed\ProductSearchAvailabilityConnector\Communication\Plugin;

use Propel\Runtime\ActiveQuery\Join;
use SprykerEngine\Shared\Dto\LocaleDto;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\QueryExpanderPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyProductTableMap;
use SprykerFeature\Zed\Stock\Persistence\Propel\Map\SpyStockProductTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;

class ProductAvailabilityQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * @return string
     */
    public function getProcessableType()
    {
        return 'product';
    }

    /**
     * @param ModelCriteria $expandableQuery
     * @param LocaleDto $locale
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, LocaleDto $locale)
    {
        $expandableQuery
            ->addJoinObject(
                new Join(
                    SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT,
                    SpyProductTableMap::COL_FK_ABSTRACT_PRODUCT,
                    Criteria::LEFT_JOIN
                ),
                'concreteProductJoin'
            );

        $expandableQuery->addJoinCondition(
            'concreteProductJoin',
            SpyProductTableMap::COL_IS_ACTIVE,
            true,
            Criteria::EQUAL
        );

        $expandableQuery->addJoin(
            SpyProductTableMap::COL_ID_PRODUCT,
            SpyStockProductTableMap::COL_FK_PRODUCT,
            Criteria::INNER_JOIN
        );
        $expandableQuery->withColumn(SpyStockProductTableMap::COL_QUANTITY, 'quantity');
        $expandableQuery->withColumn(SpyStockProductTableMap::COL_IS_NEVER_OUT_OF_STOCK, 'is_never_out_of_stock');

        return $expandableQuery;
    }

    /**
     * @return int
     */
    public function getChunkSize()
    {
        return 100;
    }
}
