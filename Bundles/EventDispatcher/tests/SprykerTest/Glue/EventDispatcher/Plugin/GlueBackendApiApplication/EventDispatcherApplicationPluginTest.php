<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\EventDispatcher\Plugin\GlueBackendApiApplication;

use Codeception\Test\Unit;
use Spryker\Glue\EventDispatcher\EventDispatcherDependencyProvider;
use Spryker\Glue\EventDispatcher\Plugin\GlueBackendApiApplication\EventDispatcherApplicationPlugin;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group EventDispatcher
 * @group Plugin
 * @group GlueBackendApiApplication
 * @group EventDispatcherApplicationPluginTest
 * Add your own group annotations below this line
 */
class EventDispatcherApplicationPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\EventDispatcher\EventDispatcherGlueTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testProvideSetsNewEventDispatcherWithPlugins(): void
    {
        //Arrange
        $container = $this->tester->createContainer();
        $eventDispatcherApplicationPlugin = $this->createBackendEventDispatcherApplicationPlugin();

        //Act
        $container = $eventDispatcherApplicationPlugin->provide($container);

        //Assert
        $this->assertTrue($container->has($this->tester::SERVICE_DISPATCHER));
        $this->assertInstanceOf(EventDispatcherInterface::class, $this->tester->getEventDispatcher($container));
    }

    /**
     * @return void
     */
    public function testProvideExtendsOldEventDispatcherWithPlugins(): void
    {
        //Arrange
        $container = $this->tester->createContainer();
        $eventDispatcherApplicationPlugin = $this->createBackendEventDispatcherApplicationPlugin();
        $container->set($this->tester::SERVICE_DISPATCHER, function (ContainerInterface $container) {
            return $this->tester->createOldEventDispatcher();
        });

        //Act
        $container = $eventDispatcherApplicationPlugin->provide($container);

        //Assert
        $this->assertTrue($container->has($this->tester::SERVICE_DISPATCHER));
        $this->assertInstanceOf(EventDispatcherInterface::class, $this->tester->getEventDispatcher($container));
    }

    /**
     * @return void
     */
    public function testProvideExtendsEventDispatcherWithPlugins(): void
    {
        // Arrange
        $container = $this->tester->createContainer();
        $eventDispatcherApplicationPlugin = $this->createBackendEventDispatcherApplicationPlugin();
        $this->tester->setDependency(EventDispatcherDependencyProvider::PLUGINS_BACKEND_EVENT_DISPATCHER, [
            $this->tester->mockEventDispatcherPlugin(),
        ]);

        //Act
        $container = $eventDispatcherApplicationPlugin->provide($container);
        $eventDispatcher = $this->tester->getEventDispatcher($container);

        //Assert
        $this->assertTrue($eventDispatcher->hasListeners($this->tester::FOO_LISTENER));
    }

    /**
     * @return \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface
     */
    protected function createBackendEventDispatcherApplicationPlugin(): ApplicationPluginInterface
    {
        return new EventDispatcherApplicationPlugin();
    }
}
