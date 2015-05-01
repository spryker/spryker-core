<?php

namespace SprykerFeature\Zed\ProductFrontendExporterAvailabilityConnector\Communication\Plugin;

use SprykerEngine\Shared\Dto\LocaleDto;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\QueryExpanderPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyProductTableMap;
use SprykerFeature\Zed\Stock\Persistence\Propel\Map\SpyStockProductTableMap;
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
