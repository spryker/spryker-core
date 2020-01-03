<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Scheduler\Communication\Plugin\Twig;

use Codeception\Test\Unit;
use Spryker\Zed\Scheduler\Communication\Twig\SchedulerTwigPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Scheduler
 * @group Communication
 * @group Plugin
 * @group Twig
 * @group SchedulerTwigPluginTest
 * Add your own group annotations below this line
 */
class SchedulerTwigPluginTest extends Unit
{
    protected const TEST_ENV_NAME = 'TEST';

    /**
     * @return void
     */
    public function testGetEnvironmentVariable(): void
    {
        $schedulerTwigPlugin = new SchedulerTwigPlugin();
        putenv('TEST=' . static::TEST_ENV_NAME);
        $environmentVariable = $schedulerTwigPlugin->getEnvironmentVariableValueByName(static::TEST_ENV_NAME);

        $this->assertEquals(static::TEST_ENV_NAME, $environmentVariable);
    }
}
