<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CategoryExporter\Communication\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Shared\Category\CategoryConfig;
use SprykerFeature\Zed\CategoryExporter\Business\CategoryExporterFacade;
use SprykerFeature\Zed\CategoryExporter\Communication\CategoryExporterDependencyContainer;
use SprykerFeature\Zed\FrontendExporter\Business\Model\BatchResultInterface;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\DataProcessorPluginInterface;

/**
 * @method CategoryExporterDependencyContainer getDependencyContainer()
 * @method CategoryExporterFacade getFacade()
 */
class CategoryNodeProcessorPlugin extends AbstractPlugin implements DataProcessorPluginInterface
{

    /**
     * @return string
     */
    public function getProcessableType()
    {
        return CategoryConfig::RESOURCE_TYPE_CATEGORY_NODE;
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
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function processData(array &$resultSet, array $processedResultSet, LocaleTransfer $locale)
    {
        $facade = $this->getFacade();

        return $facade->processCategoryNodes($resultSet, $locale);
    }

}
