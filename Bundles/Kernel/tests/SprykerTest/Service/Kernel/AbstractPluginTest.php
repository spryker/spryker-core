<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Kernel;

use Codeception\Test\Unit;
use ReflectionClass;
use Spryker\Service\Kernel\AbstractServiceFactory;
use SprykerTest\Service\Kernel\Fixtures\Plugin\FooPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
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
        $abstractFactoryMock = $this->getMockBuilder(AbstractServiceFactory::class)->disableOriginalConstructor()->getMock();
        $communicationFactoryProperty->setValue($plugin, $abstractFactoryMock);

        $factory = $plugin->getFactory();

        $this->assertInstanceOf(AbstractServiceFactory::class, $factory);
    }

    /**
     * @return void
     */
    public function testGetServiceShouldReturnInstanceIfExists()
    {
        $plugin = new FooPlugin();

        $pluginReflection = new ReflectionClass($plugin);
        $communicationFactoryProperty = $pluginReflection->getParentClass()->getProperty('service');
        $communicationFactoryProperty->setAccessible(true);
        $abstractFactoryMock = $this->getMockBuilder(AbstractServiceFactory::class)->disableOriginalConstructor()->getMock();
        $communicationFactoryProperty->setValue($plugin, $abstractFactoryMock);

        $service = $plugin->getService();

        $this->assertInstanceOf(AbstractServiceFactory::class, $service);
    }
}
