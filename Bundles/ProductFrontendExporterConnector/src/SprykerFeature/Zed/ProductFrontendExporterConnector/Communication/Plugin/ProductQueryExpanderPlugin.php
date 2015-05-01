<?php

namespace SprykerFeature\Zed\ProductFrontendExporterConnector\Communication\Plugin;

use SprykerEngine\Shared\Dto\LocaleDto;
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
     * @param LocaleDto $locale
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, LocaleDto $locale)
    {
        $queryContainer = $this->getDependencyContainer()->getProductFrontendExporterConnectorQueryContainer();

        return $queryContainer->expandQuery($expandableQuery, $locale);
    }

    /**
     * @return int
     */
    public function getChunkSize()
    {
        return 100;
    }
}
