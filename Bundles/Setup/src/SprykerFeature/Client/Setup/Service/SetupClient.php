<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Setup\Service;

use SprykerEngine\Client\Kernel\Service\AbstractClient;

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
