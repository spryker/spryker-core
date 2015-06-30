<?php

namespace SprykerFeature\Zed\GlossaryQueue\Communication;

use SprykerEngine\Shared\Kernel\Store;
use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;

class GlossaryQueueDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return Store
     */
    public function getCurrentStore()
    {
        return Store::getInstance();
    }
}
