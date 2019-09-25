<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Config;

use Codeception\Actor;
use Spryker\Shared\Config\Profiler;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class ConfigSharedTester extends Actor
{
    use _generated\ConfigSharedTesterActions;

    /**
     * @param string $expectedKey
     * @param array $profileData
     *
     * @return void
     */
    public function assertProfileKey($expectedKey, array $profileData)
    {
        $this->assertArrayHasKey(
            $expectedKey,
            $profileData,
            sprintf('The profile data for key "%s" was not found', ProfilerTest::PROFILE_KEY)
        );
    }

    /**
     * @param mixed $expected
     * @param array $profileData
     *
     * @return void
     */
    public function assertProfileValue($expected, array $profileData)
    {
        $this->assertSame($expected, $profileData[Profiler::PROFILE_VALUE]);
    }

    /**
     * @param mixed $expected
     * @param array $profileData
     *
     * @return void
     */
    public function assertProfileDefaultValue($expected, array $profileData)
    {
        $this->assertSame($expected, $profileData[Profiler::PROFILE_DEFAULT]);
    }

    /**
     * @param int $expectedCount
     * @param array $profileData
     *
     * @return void
     */
    public function assertProfileCount($expectedCount, array $profileData)
    {
        $this->assertSame(
            $profileData[Profiler::PROFILE_COUNT],
            $expectedCount,
            sprintf('The count for "%s" was expected to be "%d"', ProfilerTest::PROFILE_KEY, $expectedCount)
        );
    }
}
