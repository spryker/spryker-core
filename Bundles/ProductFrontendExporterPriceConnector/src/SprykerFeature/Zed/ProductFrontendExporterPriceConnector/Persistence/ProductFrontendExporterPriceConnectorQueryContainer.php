<?php

namespace SprykerFeature\Zed\ProductFrontendExporterPriceConnector\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Price\Persistence\Propel\SpyPriceTypeQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;

/**
 * @method ProductFrontendExporterPriceConnectorDependencyContainer getDependencyContainer()
 */
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
        return $this->getDependencyContainer()->createProductPriceExpander()->expandQuery($expandableQuery, $idPriceType);
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
