<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Availability;

use Codeception\Actor;
use Spryker\Service\Availability\AvailabilityServiceInterface;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(\SprykerTest\Service\Availability\PHPMD)
 */
class AvailabilityServiceTester extends Actor
{
    use _generated\AvailabilityServiceTesterActions;

    /**
     * @return \Spryker\Service\Availability\AvailabilityServiceInterface
     */
    public function getService(): AvailabilityServiceInterface
    {
        return $this->getLocator()->availability()->service();
    }
}
