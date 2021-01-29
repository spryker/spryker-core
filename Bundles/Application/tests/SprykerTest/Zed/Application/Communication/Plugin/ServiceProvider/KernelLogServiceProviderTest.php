<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Application\Communication\Plugin\ServiceProvider;

use Codeception\Test\Unit;
use Silex\Application;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\KernelLogServiceProvider;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @deprecated Will be removed without replacement.
 *
 * Auto-generated group annotations
 *
 * @group SprykerTest
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
    public function testRegisterShouldDoNothing(): void
    {
        $serviceProvider = new KernelLogServiceProvider();
        $serviceProvider->register(new Application());
    }

    /**
     * @return void
     */
    public function testBootShouldAddListenerToDispatcher(): void
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
