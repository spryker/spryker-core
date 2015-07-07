<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Setup\Service;

use Generated\Client\Ide\FactoryAutoCompletion\Setup;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;

/**
 * @method Setup getFactory()
 */
class SetupDependencyContainer extends AbstractServiceDependencyContainer
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
