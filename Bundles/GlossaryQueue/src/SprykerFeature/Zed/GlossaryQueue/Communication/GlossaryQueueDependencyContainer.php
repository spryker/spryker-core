<?php

namespace SprykerFeature\Zed\GlossaryQueue\Communication;

use SprykerEngine\Shared\Kernel\Store;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;

class GlossaryQueueDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return Store
     */
    public function getCurrentStore()
    {
        return Store::getInstance();
    }

}
