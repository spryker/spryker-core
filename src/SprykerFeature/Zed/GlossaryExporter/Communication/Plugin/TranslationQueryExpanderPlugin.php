<?php

namespace SprykerFeature\Zed\GlossaryExporter\Communication\Plugin;

use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\QueryExpanderPluginInterface;
use SprykerFeature\Zed\GlossaryExporter\Communication\GlossaryExporterDependencyContainer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use Propel\Runtime\ActiveQuery\ModelCriteria;

/**
 * @method GlossaryExporterDependencyContainer getDependencyContainer()
 */
class TranslationQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * @return string
     */
    public function getProcessableType()
    {
        return 'translation';
    }

    /**
     * @param ModelCriteria $expandableQuery
     * @param string $localeName
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, $localeName)
    {
        return $this->getDependencyContainer()->getGlossaryQueryContainer()->expandQuery($expandableQuery, $localeName);
    }

    /**
     * @return int
     */
    public function getChunkSize()
    {
        return 500;
    }
}
