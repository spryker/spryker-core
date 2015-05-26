<?php

namespace SprykerFeature\Zed\TaxFrontendExporterConnector\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Price\Persistence\Propel\SpyPriceTypeQuery;
use Propel\Runtime\ActiveQuery\ModelCriteria;

/**
 * @method TaxFrontendExporterConnectorDependencyContainer getDependencyContainer()
 */
class TaxFrontendExporterConnectorQueryContainer extends AbstractQueryContainer
{
    /**
     * @param ModelCriteria $expandableQuery
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery)
    {
        return $this->getDependencyContainer()->createProductTaxExpander()->expandQuery($expandableQuery);
    }
}
