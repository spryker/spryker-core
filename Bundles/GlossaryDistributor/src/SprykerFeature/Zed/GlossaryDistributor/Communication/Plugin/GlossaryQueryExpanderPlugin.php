<?php

namespace SprykerFeature\Zed\GlossaryDistributor\Communication\Plugin;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Distributor\Dependency\Plugin\QueryExpanderPluginInterface;
use SprykerFeature\Zed\GlossaryDistributor\Persistence\GlossaryDistributorQueryContainer;

/**
 * @method GlossaryDistributorQueryContainer getQueryContainer()
 */
class GlossaryQueryExpanderPlugin extends AbstractPlugin implements
    QueryExpanderPluginInterface
{

    /**
     * @return string
     */
    public function getType()
    {
        return 'glossary_translation';
    }

    /**
     * @param ModelCriteria $expandableQuery
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery)
    {
        return $this->getQueryContainer()->queryTranslationsToDistribute($expandableQuery);
    }

    /**
     * @return int
     */
    public function getChunkSize()
    {
        return 100;
    }
}
