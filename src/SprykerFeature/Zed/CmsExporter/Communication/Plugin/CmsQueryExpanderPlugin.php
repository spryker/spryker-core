<?php

namespace SprykerFeature\Zed\CmsExporter\Communication\Plugin;

use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\QueryExpanderPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerFeature\Zed\CmsExporter\Communication\CmsExporterDependencyContainer;

/**
 * @method CmsExporterDependencyContainer getDependencyContainer()
 */
class CmsQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * @return string
     */
    public function getProcessableType()
    {
        return 'page';
    }

    /**
     * @param ModelCriteria $expandableQuery
     * @param string $localeName
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, $localeName)
    {
        $queryContainer = $this->getDependencyContainer()->getCmsExporterQueryContainer();

        return $queryContainer->expandCmsPageQuery($expandableQuery);
    }

    /**
     * @return int
     */
    public function getChunkSize()
    {
        return 500;
    }
}
