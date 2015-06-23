<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Sdk\Setup;

use SprykerEngine\Sdk\Kernel\AbstractSdk;

/**
 * Class SetupSdk
 * @package SprykerFeature\Sdk\Setup
 */
class SetupSdk extends AbstractSdk
{
    /**
     * @return mixed
     */
    public function getHeartbeatResponse()
    {
        return $this->getDependencyContainer()->getHearbeatResponse();
    }
}
