<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Application\Plugin\ServiceProvider;

use Codeception\Test\Unit;
use ReflectionClass;
use Silex\Application;
use Spryker\Shared\Application\Log\Config\SprykerLoggerConfig;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Log\LogConstants;
use Spryker\Yves\Application\Plugin\ServiceProvider\KernelLogServiceProvider;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Application
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
    public function setUp()
    {
        $reflection = new ReflectionClass(Config::class);
        $reflectionProperty = $reflection->getProperty('config');
        $reflectionProperty->setAccessible(true);
        $config = $reflectionProperty->getValue();
        $config[LogConstants::LOGGER_CONFIG] = SprykerLoggerConfig::class;
    }

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
