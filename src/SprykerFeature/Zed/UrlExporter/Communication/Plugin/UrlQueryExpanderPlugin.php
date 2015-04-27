<?php

namespace SprykerFeature\Zed\UrlExporter\Communication\Plugin;

use SprykerEngine\Shared\Dto\LocaleDto;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\QueryExpanderPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerFeature\Zed\UrlExporter\Communication\UrlExporterDependencyContainer;

/**
 * @method UrlExporterDependencyContainer getDependencyContainer()
 */
class UrlQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * @return string
     */
    public function getProcessableType()
    {
        return 'url';
    }

    /**
     * @param ModelCriteria $expandableQuery
     * @param LocaleDto $locale
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, LocaleDto $locale)
    {
        $queryContainer = $this->getDependencyContainer()->getUrlExporterQueryContainer();

        return $queryContainer->expandUrlQuery($expandableQuery);
    }

    /**
     * @return int
     */
    public function getChunkSize()
    {
        return 500;
    }
}
