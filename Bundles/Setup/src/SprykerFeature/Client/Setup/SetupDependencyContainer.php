<?php

namespace SprykerFeature\Client\Setup;

use Generated\Client\Ide\FactoryAutoCompletion\Setup;
use SprykerEngine\Client\Kernel\AbstractDependencyContainer;

/**
 * Class SetupDependencyContainer
 * @package SprykerFeature\Client\Setup
 */
class SetupDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @var Setup
     */
    protected $factory;

    /**
     * @return array
     */
    public function getHeartbeatResponse()
    {
        return $this->getFactory()->createHeartbeat(
            $this->getLocator()->kvStorage()->readClient()->getInstance(),
            $this->getLocator()->search()->indexClient()->getInstance()
        );
    }
}
