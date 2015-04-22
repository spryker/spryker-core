<?php

namespace SprykerFeature\Zed\ProductFrontendExporterConnector\Communication\Plugin;

use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\QueryExpanderPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\ProductFrontendExporterConnector\Communication\ProductFrontendExporterConnectorDependencyContainer;
use Propel\Runtime\ActiveQuery\ModelCriteria;

/**
 * @method ProductFrontendExporterConnectorDependencyContainer getDependencyContainer()
 */
class ProductQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
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
     * @param string $localeName
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, $localeName)
    {
        $queryContainer = $this->getDependencyContainer()->getProductFrontendExporterConnectorQueryContainer();

        return $queryContainer->expandQuery($expandableQuery, $localeName);
    }

    /**
     * @return int
     */
    public function getChunkSize()
    {
        return 100;
    }
}
