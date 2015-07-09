<?php

namespace SprykerFeature\Zed\GlossaryDistributor\Communication\Plugin;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Distributor\Dependency\Plugin\DistributorQueryExpanderPluginInterface;
use SprykerFeature\Zed\GlossaryDistributor\Business\GlossaryDistributorFacade;
use SprykerFeature\Zed\GlossaryDistributor\Communication\GlossaryDistributorDependencyContainer;

/**
 * @method GlossaryDistributorDependencyContainer getDependencyContainer()
 * @method GlossaryDistributorFacade getFacade()
 */
class GlossaryQueryExpanderPlugin extends AbstractPlugin implements
    DistributorQueryExpanderPluginInterface
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
        return $this->getFacade()
            ->expandTranslationQueryToDistribute($expandableQuery)
        ;
    }

    /**
     * @return int
     */
    public function getChunkSize()
    {
        return 100;
    }

}
