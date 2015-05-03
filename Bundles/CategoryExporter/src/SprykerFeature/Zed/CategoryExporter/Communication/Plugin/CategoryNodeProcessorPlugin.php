<?php

namespace SprykerFeature\Zed\CategoryExporter\Communication\Plugin;

use SprykerEngine\Shared\Locale\Dto\LocaleDto;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Shared\Category\CategoryResourceSettings;
use SprykerFeature\Zed\CategoryExporter\Communication\CategoryExporterDependencyContainer;
use SprykerFeature\Zed\FrontendExporter\Business\Model\BatchResultInterface;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\DataProcessorPluginInterface;

/**
 * @method CategoryExporterDependencyContainer getDependencyContainer()
 */
class CategoryNodeProcessorPlugin extends AbstractPlugin implements DataProcessorPluginInterface
{
    /**
     * @return string
     */
    public function getProcessableType()
    {
        return CategoryResourceSettings::RESOURCE_TYPE_CATEGORY_NODE;
    }

    /**
     * @param BatchResultInterface $results
     *
     * @return bool
     */
    public function isFailed(BatchResultInterface $results)
    {
        return $results->getFailedCount() > 0;
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
        $facade = $this->getDependencyContainer()->getCategoryExporterFacade();

        return $facade->processCategoryNodes($resultSet, $locale);
    }
}
