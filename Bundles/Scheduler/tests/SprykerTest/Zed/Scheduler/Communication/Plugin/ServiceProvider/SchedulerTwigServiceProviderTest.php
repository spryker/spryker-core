<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Scheduler\Communication\Plugin\ServiceProvider;

use Codeception\Test\Unit;
use Silex\Application;
use Spryker\Zed\Scheduler\Communication\Plugin\ServiceProvider\SchedulerTwigServiceProvider;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Scheduler
 * @group Communication
 * @group Plugin
 * @group ServiceProvider
 * @group SchedulerTwigServiceProviderTest
 * Add your own group annotations below this line
 */
class SchedulerTwigServiceProviderTest extends Unit
{
    /**
     * @return void
     */
    public function testRegisterSchedulerGetEnvExtension(): void
    {
        $application = new Application();
        $schedulerTwigService = new SchedulerTwigServiceProvider();
        $schedulerTwigService->register($application);
    }
}
