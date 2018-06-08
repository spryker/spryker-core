<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Config;

use Codeception\Test\Unit;
use Spryker\Shared\Config\Profiler;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Shared
 * @group Config
 * @group ProfilerTest
 * Add your own group annotations below this line
 */
class ProfilerTest extends Unit
{
    const PROFILE_KEY = 'profile-key';

    /**
     * @var \SprykerTest\Shared\Config\ConfigSharedTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testWhenConfigHasKeyAddShouldCreateNewProfileDataFromValue()
    {
        $profiler = new Profiler();
        $profiler->add(static::PROFILE_KEY, null, 'value');

        $profileData = $profiler->getProfileData();

        $this->tester->assertProfileKey(static::PROFILE_KEY, $profileData);
        $this->tester->assertProfileValue('value', $profileData[static::PROFILE_KEY]);
    }

    /**
     * @return void
     */
    public function testWhenConfigValueIsObjectClassNameOfValueShouldBeUsed()
    {
        $profiler = new Profiler();
        $profiler->add(static::PROFILE_KEY, null, $this);

        $profileData = $profiler->getProfileData();

        $this->tester->assertProfileKey(static::PROFILE_KEY, $profileData);
        $this->tester->assertProfileValue(get_class($this), $profileData[static::PROFILE_KEY]);
    }

    /**
     * @return void
     */
    public function testFormatValue()
    {
        $profiler = new Profiler();
        $profiler->add(static::PROFILE_KEY, null, ['class' => $this, 'bool' => true, 'emptyArray' => []]);

        $profileData = $profiler->getProfileData();

        $expectedProfileData = [
            static::PROFILE_KEY => [
                Profiler::PROFILE_VALUE => [
                    'class' => get_class($this),
                    'bool' => 'true',
                    'emptyArray' => '[]',
                ],
                Profiler::PROFILE_DEFAULT => null,
                Profiler::PROFILE_COUNT => 1,
            ],
        ];
        $this->tester->assertProfileKey(static::PROFILE_KEY, $profileData);
        $this->assertSame($expectedProfileData, $profileData);
    }

    /**
     * @return void
     */
    public function testWhenConfigNotHasKeyAddShouldCreateNewProfileDataFromDefaultValue()
    {
        $profiler = new Profiler();
        $profiler->add(static::PROFILE_KEY, 'default', null);

        $profileData = $profiler->getProfileData();

        $this->tester->assertProfileKey(static::PROFILE_KEY, $profileData);
        $this->tester->assertProfileValue(null, $profileData[static::PROFILE_KEY]);
    }

    /**
     * @return void
     */
    public function testWhenConfigDefaultValueIsObjectClassNameOfDefaultValueShouldBeUsed()
    {
        $profiler = new Profiler();
        $profiler->add(static::PROFILE_KEY, $this, null);

        $profileData = $profiler->getProfileData();

        $this->tester->assertProfileKey(static::PROFILE_KEY, $profileData);
        $this->tester->assertProfileDefaultValue(get_class($this), $profileData[static::PROFILE_KEY]);
    }

    /**
     * @return void
     */
    public function testWhenProfileDataForKeyExistsAddShouldIncreaseProfileDataCount()
    {
        $profiler = new Profiler();
        $profiler->add(static::PROFILE_KEY, null, 'value');
        $profiler->add(static::PROFILE_KEY, null, 'value');

        $profileData = $profiler->getProfileData();
        $this->tester->assertProfileCount(2, $profileData[static::PROFILE_KEY]);
    }
}
