<?php

namespace SprykerFeature\Zed\ProductFrontendExporterConnector\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use Propel\Runtime\ActiveQuery\ModelCriteria;

/**
 * @method ProductFrontendExporterConnectorDependencyContainer getDependencyContainer()
 */
class ProductFrontendExporterConnectorQueryContainer extends AbstractQueryContainer implements ProductFrontendExporterConnectorQueryContainerInterface
{
    /**
     * @param ModelCriteria $expandableQuery
     * @param string $localeName
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, $localeName)
    {
        return $this->getDependencyContainer()->getProductQueryExpander()->expandQuery($expandableQuery, $localeName);
    }
}
