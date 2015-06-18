<?php

namespace SprykerFeature\Client\Setup;

use SprykerEngine\Client\Kernel\AbstractStub;

class SetupStub extends AbstractStub
{
    /**
     * @return mixed
     */
    public function getHeartbeatResponse()
    {
        return $this->getDependencyContainer()->getHearbeatResponse();
    }
}
