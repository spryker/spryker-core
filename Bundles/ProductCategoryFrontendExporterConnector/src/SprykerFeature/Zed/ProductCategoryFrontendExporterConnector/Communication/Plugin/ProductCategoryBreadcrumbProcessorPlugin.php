<?php

namespace SprykerFeature\Zed\ProductCategoryFrontendExporterConnector\Communication\Plugin;

use SprykerEngine\Shared\Locale\Dto\LocaleDto;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\DataProcessorPluginInterface;
use SprykerFeature\Zed\ProductCategoryFrontendExporterConnector\Communication\ProductCategoryFrontendExporterConnectorDependencyContainer;

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
        return 'abstract_product';
    }

    /**
     * @param array $resultSet
     * @param array $processedResultSet
     * @param LocaleDto $locale
     *
     * @return array
     */
    public function processData(array &$resultSet, array $processedResultSet, LocaleDto $locale)
    {
        $facade = $this->getDependencyContainer()->getProductCategoryFrontendExporterFacade();

        return $facade->processProductCategoryBreadcrumbs($resultSet, $processedResultSet, $locale);
    }
}
