<?php

namespace SprykerFeature\Zed\GlossaryDistributor\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\GlossaryDistributor\Persistence\GlossaryDistributorQueryContainer;

class GlossaryDistributorDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return GlossaryDistributorQueryContainer
     */
    public function getGlossaryDistributorQueryContainer()
    {
        return $this->getQueryContainer();
    }

}
