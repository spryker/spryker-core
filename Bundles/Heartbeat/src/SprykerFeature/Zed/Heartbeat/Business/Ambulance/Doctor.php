<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Heartbeat\Business\Check;

use SprykerFeature\Shared\Heartbeat\Business\Ambulance\HealthIndicatorInterface;

class Doctor implements HealthIndicatorInterface
{

    /**
     * @var HealthIndicatorInterface[]
     */
    protected $heartbeatChecker;

    /**
     * @param HealthIndicatorInterface[] $heartbeatChecker
     */
    public function __construct(array $heartbeatChecker)
    {
        $this->heartbeatChecker = $heartbeatChecker;
    }

    /**
     * @return bool
     */
    public function check()
    {
        $heartbeats = true;
        foreach ($this->heartbeatChecker as $heartbeatChecker) {
            if (!$heartbeatChecker->check()) {
                $heartbeats = false;
            }
        }

        return $heartbeats;
    }

}
