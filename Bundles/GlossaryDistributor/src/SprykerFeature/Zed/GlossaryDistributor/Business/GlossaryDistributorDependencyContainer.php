<?php

namespace SprykerFeature\Zed\GlossaryDistributor\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
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
