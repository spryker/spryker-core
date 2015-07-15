<?php

namespace SprykerFeature\Zed\GlossaryDistributor\Business;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method GlossaryDistributorDependencyContainer getDependencyContainer()
 */
class GlossaryDistributorFacade extends AbstractFacade
{

    /**
     * @param ModelCriteria $expandQuery
     *
     * @return ModelCriteria
     */
    public function expandTranslationQueryToDistribute(ModelCriteria $expandQuery)
    {
        return $this->getDependencyContainer()
            ->getGlossaryDistributorQueryContainer()
            ->expandTranslationQueryToDistribute($expandQuery)
            ;
    }

}
