<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Scheduler\Communication\Plugin\ServiceProvider;

use Codeception\Test\Unit;
use Silex\Application;
use Spryker\Zed\Scheduler\Communication\Plugin\ServiceProvider\SchedulerTwigServiceProvider;
use Spryker\Zed\Scheduler\Communication\Twig\SchedulerTwigPlugin;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Auto-generated group annotations
 *
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
    public function testRegisterSchedulerGetEnvAddsExtensionToTwig(): void
    {
        $applicationMock = $this->getApplicationMock();
        $schedulerTwigService = new SchedulerTwigServiceProvider();
        $schedulerTwigService->register($applicationMock);

        $twig = $applicationMock['twig'];

        $this->assertTrue($twig->hasExtension(SchedulerTwigPlugin::class));
    }

    /**
     * @return \Silex\Application
     */
    protected function getApplicationMock(): Application
    {
        $application = new Application();
        $application['twig'] = function () {
            return new Environment(new FilesystemLoader());
        };

        return $application;
    }
}
