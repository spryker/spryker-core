<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\TaxFrontendExporterConnector\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
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
