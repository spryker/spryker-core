<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Service\Kernel;

use PHPUnit_Framework_TestCase;
use ReflectionClass;
use Spryker\Service\Kernel\AbstractServiceFactory;
use Unit\Spryker\Service\Kernel\Fixtures\Plugin\FooPlugin;

/**
 * @group Unit
 * @group Spryker
 * @group Service
 * @group Kernel
 * @group AbstractPluginTest
 */
class AbstractPluginTest extends PHPUnit_Framework_TestCase
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
