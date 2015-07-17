<?php

namespace SprykerFeature\Zed\GlossaryDistributor\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\GlossaryDistributor\Persistence\GlossaryDistributorQueryContainer;

class GlossaryDistributorDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return GlossaryDistributorQueryContainer
     */
    public function getGlossaryDistributorQueryContainer()
    {
        return $this->getQueryContainer();
    }

}
