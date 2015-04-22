<?php

namespace SprykerFeature\Zed\ProductCategoryFrontendExporterConnector\Communication\Plugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\DataProcessorPluginInterface;
use SprykerFeature\Zed\ProductCategoryFrontendExporterConnector\Communication\ProductCategoryFrontendExporterConnectorDependencyContainer;

/**
 * Class ProductBreadcrumProcessorPlugin
 * @package SprykerFeature\Zed\ProductCategory\Communication\Plugin
 */
/**
 * @method ProductCategoryFrontendExporterConnectorDependencyContainer getDependencyContainer()
 */
class ProductCategoryBreadcrumbProcessorPlugin extends AbstractPlugin implements
    DataProcessorPluginInterface
{
    /**
     * @return string
     */
    public function getProcessableType()
    {
        return 'product';
    }

    /**
     * @param array $resultSet
     * @param array $processedResultSet
     * @param string $locale
     *
     * @return array
     */
    public function processData(array &$resultSet, array $processedResultSet, $locale)
    {
        $facade = $this->getDependencyContainer()->getProductCategoryFrontendExporterFacade();

        return $facade->processProductCategoryBreadcrumbs($resultSet, $processedResultSet, $locale);
    }
}
