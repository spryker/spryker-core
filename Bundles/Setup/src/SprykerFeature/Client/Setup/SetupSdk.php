<?php

namespace SprykerFeature\Client\Setup;

use SprykerEngine\Client\Kernel\AbstractClient;

/**
 * Class SetupClient
 * @package SprykerFeature\Client\Setup
 */
class SetupClient extends AbstractClient
{
    /**
     * @return mixed
     */
    public function getHeartbeatResponse()
    {
        return $this->getDependencyContainer()->getHearbeatResponse();
    }
}
