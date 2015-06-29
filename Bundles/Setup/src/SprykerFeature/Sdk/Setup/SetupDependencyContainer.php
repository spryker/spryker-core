<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Sdk\Setup;

use Generated\Sdk\Ide\FactoryAutoCompletion\Setup;
use SprykerEngine\Sdk\Kernel\AbstractDependencyContainer;

/**
 * Class SetupDependencyContainer
 * @package SprykerFeature\Sdk\Setup
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
