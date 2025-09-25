<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilDateTime;

use Codeception\Actor;

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
 * @SuppressWarnings(PHPMD)
 */
class UtilDateTimeServiceTester extends Actor
{
    use _generated\UtilDateTimeServiceTesterActions;

    /**
     * @var string
     */
    public const TEST_DATE_TIME_ZONE = 'Europe/Vilnius';

    /**
     * @var string
     */
    protected const SERVICE_TIMEZONE = 'SERVICE_TIMEZONE';

    /**
     * @param string|null $timezone
     *
     * @return void
     */
    public function setTimezoneService(?string $timezone = null): void
    {
        $this->getContainer()
            ->set(static::SERVICE_TIMEZONE, $timezone);
    }
}
