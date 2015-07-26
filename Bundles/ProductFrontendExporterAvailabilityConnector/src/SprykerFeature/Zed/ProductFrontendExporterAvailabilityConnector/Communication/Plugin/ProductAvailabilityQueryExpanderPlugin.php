<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductFrontendExporterAvailabilityConnector\Communication\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Collector\Dependency\Plugin\QueryExpanderPluginInterface;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyProductTableMap;
use SprykerFeature\Zed\Stock\Persistence\Propel\Map\SpyStockProductTableMap;

class ProductAvailabilityQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{

    /**
     * @return string
     */
    public function getProcessableType()
    {
        return 'abstract_product';
    }

    /**
     * @param ModelCriteria $expandableQuery
     * @param LocaleTransfer $locale
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, LocaleTransfer $locale)
    {
        $expandableQuery->addAsColumn(
            'quantity',
            sprintf(
                '(SELECT SUM(%s) FROM %s WHERE %s = %s)',
                SpyStockProductTableMap::COL_QUANTITY,
                SpyStockProductTableMap::TABLE_NAME,
                SpyStockProductTableMap::COL_FK_PRODUCT,
                SpyProductTableMap::COL_ID_PRODUCT
            )
        );

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
