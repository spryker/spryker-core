<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\EventDispatcher\Plugin\Application;

use Codeception\Test\Unit;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Yves\EventDispatcher\Plugin\Application\EventDispatcherApplicationPlugin;
use Spryker\Yves\Kernel\Container;
use Symfony\Component\EventDispatcher\EventDispatcher as SymfonyEventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group EventDispatcher
 * @group Plugin
 * @group Application
 * @group EventDispatcherApplicationPluginTest
 * Add your own group annotations below this line
 */
class EventDispatcherApplicationPluginTest extends Unit
{
    public const SERVICE_DISPATCHER = 'dispatcher';
    public const DUMMY_EVENT = 'DUMMY_EVENT';

    /**
     * @return void
     */
    public function testEventDispatcherSetNewDispatcher(): void
    {
        //Arrange
        $container = $this->createContainer();
        $eventDispatcherApplicationPlugin = $this->createEventDispatcherApplicationPlugin();

        //Act
        $container = $eventDispatcherApplicationPlugin->provide($container);

        //Assert
        $this->assertTrue($container->has(static::SERVICE_DISPATCHER));
        $this->assertInstanceOf(EventDispatcherInterface::class, $container->get(static::SERVICE_DISPATCHER));
    }

    /**
     * @return void
     */
    public function testEventDispatcherExtendOldDispatcher(): void
    {
        //Arrange
        $container = $this->createContainer();
        $eventDispatcherApplicationPlugin = $this->createEventDispatcherApplicationPlugin();
        $container->set(static::SERVICE_DISPATCHER, function (ContainerInterface $container) {
            return new SymfonyEventDispatcher();
        });

        //Act
        $container = $eventDispatcherApplicationPlugin->provide($container);

        //Assert
        $this->assertTrue($container->has(static::SERVICE_DISPATCHER));
        $this->assertInstanceOf(EventDispatcherInterface::class, $container->get(static::SERVICE_DISPATCHER));
    }

    /**
     * @return void
     */
    public function testNewEventSubscriberHasListenersFromExistingEventDispatcher(): void
    {
        //Arrange
        $container = $this->createContainer();
        $eventDispatcherApplicationPlugin = $this->createEventDispatcherApplicationPlugin();
        $container->set(static::SERVICE_DISPATCHER, function (ContainerInterface $container) {
            $eventDispatcher = new SymfonyEventDispatcher();

            $eventDispatcher->addSubscriber($this->createDummyEventSubscriber());

            return $eventDispatcher;
        });

        //Act
        $container = $eventDispatcherApplicationPlugin->provide($container);

        //Assert
        $this->assertTrue($container->has(static::SERVICE_DISPATCHER));
        $eventDispatcher = $this->getEventDispatcher($container);
        $this->assertNotEmpty($eventDispatcher->getListeners());
    }

    /**
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function createContainer(): ContainerInterface
    {
        return new Container();
    }

    /**
     * @return \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface
     */
    protected function createEventDispatcherApplicationPlugin(): ApplicationPluginInterface
    {
        return new EventDispatcherApplicationPlugin();
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
    protected function createDummyEventSubscriber(): EventSubscriberInterface
    {
        return new class implements EventSubscriberInterface
        {
            /**
             * @return array
             */
            public static function getSubscribedEvents()
            {
                return [
                    EventDispatcherApplicationPluginTest::DUMMY_EVENT => 'onDummyEvent',
                ];
            }

            /**
             * @return void
             */
            public function onDummyEvent(): void
            {
            }
        };
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    protected function getEventDispatcher(ContainerInterface $container): EventDispatcherInterface
    {
        return $container->get(static::SERVICE_DISPATCHER);
    }
}
