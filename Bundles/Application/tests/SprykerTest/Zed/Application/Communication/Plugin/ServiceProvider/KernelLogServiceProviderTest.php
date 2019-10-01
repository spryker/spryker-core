<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Application\Communication\Plugin\ServiceProvider;

use Codeception\Test\Unit;
use Silex\Application;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\KernelLogServiceProvider;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Auto-generated group annotations
 *
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Application
 * @group Communication
 * @group Plugin
 * @group ServiceProvider
 * @group KernelLogServiceProviderTest
 * Add your own group annotations below this line
 */
class KernelLogServiceProviderTest extends Unit
{
    /**
     * @return void
     */
    public function testRegisterShouldDoNothing()
    {
        $serviceProvider = new KernelLogServiceProvider();
        $serviceProvider->register(new Application());
    }

    /**
     * @return void
     */
    public function testBootShouldAddListenerToDispatcher()
    {
        $application = new Application();
        $dispatcher = new EventDispatcher();
        $application['dispatcher'] = $dispatcher;

        $serviceProvider = new KernelLogServiceProvider();
        $serviceProvider->boot($application);

        $this->assertTrue($dispatcher->hasListeners('kernel.request'));
        $this->assertTrue($dispatcher->hasListeners('kernel.response'));
    }
}
