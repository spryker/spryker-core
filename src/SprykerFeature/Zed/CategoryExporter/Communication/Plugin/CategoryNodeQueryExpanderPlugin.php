<?php

namespace SprykerFeature\Zed\CategoryExporter\Communication\Plugin;

use SprykerFeature\Zed\CategoryExporter\Communication\CategoryExporterDependencyContainer;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\QueryExpanderPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerFeature\Shared\Category\CategoryResourceSettings;

/**
 * @method CategoryExporterDependencyContainer getDependencyContainer()
 */
class CategoryNodeQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * @return string
     */
    public function getProcessableType()
    {
        return CategoryResourceSettings::RESOURCE_TYPE_CATEGORY_NODE;
    }

    /**
     * @param ModelCriteria $expandableQuery
     * @param string $localeName
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, $localeName)
    {
        $queryContainer = $this->getDependencyContainer()->getCategoryExporterQueryContainer();

        return $queryContainer->expandCategoryNodeQuery($expandableQuery, $localeName);
    }

    /**
     * @return int
     */
    public function getChunkSize()
    {
        return 100;
    }
}
