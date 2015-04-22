<?php

namespace SprykerFeature\Zed\CategoryExporter\Communication\Plugin;

use SprykerFeature\Zed\CategoryExporter\Communication\CategoryExporterDependencyContainer;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\QueryExpanderPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use Propel\Runtime\ActiveQuery\ModelCriteria;

/**
 * Class CategoryNodeQueryExpanderPlugin
 * @package SprykerFeature\Zed\CategoryExporter\Communication\Plugin
 */
/**
 * @method CategoryExporterDependencyContainer getDependencyContainer()
 */
class NavigationQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * @return string
     */
    public function getProcessableType()
    {
        return 'navigation';
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

        return $queryContainer->expandNavigationQuery($expandableQuery, $localeName);
    }

    /**
     * @return int
     */
    public function getChunkSize()
    {
        return 100;
    }
}
