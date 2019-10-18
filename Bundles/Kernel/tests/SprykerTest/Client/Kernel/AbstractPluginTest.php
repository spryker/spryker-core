<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Kernel;

use Codeception\Test\Unit;
use ReflectionClass;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\Kernel\AbstractFactory;
use SprykerTest\Client\Kernel\Fixtures\Plugin\FooPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Kernel
 * @group AbstractPluginTest
 * Add your own group annotations below this line
 */
class AbstractPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testGetFactoryShouldReturnInstanceIfExists()
    {
        $plugin = new FooPlugin();

        $pluginReflection = new ReflectionClass($plugin);
        $communicationFactoryProperty = $pluginReflection->getParentClass()->getProperty('factory');
        $communicationFactoryProperty->setAccessible(true);
        $abstractFactoryMock = $this->getMockBuilder(AbstractFactory::class)->disableOriginalConstructor()->getMock();
        $communicationFactoryProperty->setValue($plugin, $abstractFactoryMock);

        $factory = $plugin->getFactory();

        $this->assertInstanceOf(AbstractFactory::class, $factory);
    }

    /**
     * @return void
     */
    public function testGetClientShouldReturnInstanceIfExists()
    {
        $plugin = new FooPlugin();

        $pluginReflection = new ReflectionClass($plugin);
        $communicationFactoryProperty = $pluginReflection->getParentClass()->getProperty('client');
        $communicationFactoryProperty->setAccessible(true);
        $abstractFactoryMock = $this->getMockBuilder(AbstractClient::class)->disableOriginalConstructor()->getMock();
        $communicationFactoryProperty->setValue($plugin, $abstractFactoryMock);

        $client = $plugin->getClient();

        $this->assertInstanceOf(AbstractClient::class, $client);
    }
}
