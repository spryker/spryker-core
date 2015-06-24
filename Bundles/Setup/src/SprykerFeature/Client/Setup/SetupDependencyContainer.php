<?php

namespace SprykerFeature\Client\Setup;

use Generated\Client\Ide\FactoryAutoCompletion\Setup;
use SprykerEngine\Client\Kernel\AbstractDependencyContainer;

/**
 * @method Setup getFactory()
 */
class SetupDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return array
     */
    public function getHeartbeatResponse()
    {
        return $this->getFactory()->createHeartbeat(
            $this->getLocator()->kvStorage()->client(),
            $this->getLocator()->search()->indexClient()->getInstance()
        );
    }
}
