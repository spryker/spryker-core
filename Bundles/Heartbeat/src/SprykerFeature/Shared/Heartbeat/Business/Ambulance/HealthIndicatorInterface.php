<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Heartbeat\Business\Ambulance;

interface HealthIndicatorInterface
{

    /**
     * @return bool
     */
    public function check();

}
