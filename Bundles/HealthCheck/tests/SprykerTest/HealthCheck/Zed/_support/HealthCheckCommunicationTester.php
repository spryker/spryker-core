<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\HealthCheck;

use Codeception\Actor;
use Spryker\Shared\HealthCheck\HealthCheckConstants;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class HealthCheckCommunicationTester extends Actor
{
    use _generated\HealthCheckCommunicationTesterActions;

    /**
     * @return void
     */
    public function enableHealthCheckEndpoints(): void
    {
        $this->setConfig(HealthCheckConstants::HEALTH_CHECK_ENABLED, true);
    }

    /**
     * @return void
     */
    public function disableHealthCheckEndpoints(): void
    {
        $this->setConfig(HealthCheckConstants::HEALTH_CHECK_ENABLED, false);
    }
}
