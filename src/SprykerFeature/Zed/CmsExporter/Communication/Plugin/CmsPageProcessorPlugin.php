<?php

namespace SprykerFeature\Zed\CmsExporter\Communication\Plugin;

use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\DataProcessorPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\CmsExporter\Communication\CmsExporterDependencyContainer;

/**
 * @method CmsExporterDependencyContainer getDependencyContainer()
 */
class CmsPageProcessorPlugin extends AbstractPlugin implements DataProcessorPluginInterface
{
    /**
     * @return string
     */
    public function getProcessableType()
    {
        return 'page';
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
        $processedResultSet = $this->getDependencyContainer()->getCmsExporterFacade()->buildPages($resultSet, $locale);

        return $processedResultSet;
    }
}