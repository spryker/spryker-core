<?php

namespace SprykerFeature\Zed\GlossaryDistributor\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\Zed\GlossaryDistributor\Persistence\GlossaryDistributorQueryContainer;

class GlossaryDistributorDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return GlossaryDistributorQueryContainer
     */
    public function getGlossaryDistributorQueryContainer()
    {
        return $this->getQueryContainer();
    }
}
