<?php

namespace SprykerFeature\Zed\ProductFrontendExporterPriceConnector\Communication\Plugin;

use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\QueryExpanderPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\ProductFrontendExporterPriceConnector\Communication\ProductFrontendExporterPriceConnectorDependencyContainer;
use Propel\Runtime\ActiveQuery\ModelCriteria;

/**
 * @method ProductFrontendExporterPriceConnectorDependencyContainer getDependencyContainer()
 */
class ProductPriceQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
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
        $priceTypeEntity = $this->getDependencyContainer()->getQueryContainer()->getFkDefaultPriceType($this->getDefaultPriceType())->findOne();
        return $this->getDependencyContainer()->getQueryContainer()->expandQuery($expandableQuery, $priceTypeEntity->getIdPriceType());
    }

    /**
     * @return string
     */
    protected function getDefaultPriceType()
    {
        return $this->getDependencyContainer()->getPriceProcessor()->getDefaultPriceType();
    }
    /**
     * @return int
     */
    public function getChunkSize()
    {
        return 100;
    }

}
