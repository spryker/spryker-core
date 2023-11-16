<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\EventDispatcher;

use Codeception\Actor;
use Codeception\Stub;
use Spryker\Glue\Kernel\Container;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use SprykerTest\Glue\EventDispatcher\Plugin\Application\EventDispatcherApplicationPluginTest;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcher as SymfonyEventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(\SprykerTest\Glue\EventDispatcher\PHPMD)
 */
class EventDispatcherGlueTester extends Actor
{
    use _generated\EventDispatcherGlueTesterActions;

    /**
     * @var string
     */
    public const SERVICE_DISPATCHER = 'dispatcher';

    /**
     * @var string
     */
    public const SERVICE_STOPWATCH = 'stopwatch';

    /**
     * @var string
     */
    public const FOO_LISTENER = 'foo';

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provideTraceableEventDispatcher(ContainerInterface $container): ContainerInterface
    {
        $eventDispatcher = new EventDispatcher();
        $traceableEventDispatcher = new TraceableEventDispatcher($eventDispatcher, new Stopwatch());

        $container->set(static::SERVICE_DISPATCHER, function () use ($traceableEventDispatcher) {
            return $traceableEventDispatcher;
        });

        $container->set(static::SERVICE_STOPWATCH, function () {
            return new Stopwatch();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface
     */
    public function mockEventDispatcherPlugin(): EventDispatcherPluginInterface
    {
        /** @var \Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface $eventDispatcherPluginMock */
        $eventDispatcherPluginMock = Stub::makeEmpty(EventDispatcherPluginInterface::class, [
            'extend' => function (EventDispatcher $eventDispatcher) {
                $eventDispatcher->addListener(static::FOO_LISTENER, function () {
                    return 'bar';
                });

                return $eventDispatcher;
            },
        ]);

        return $eventDispatcherPluginMock;
    }

    /**
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function createContainer(): ContainerInterface
    {
        return new Container();
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
    public function createDummyEventSubscriber(): EventSubscriberInterface
    {
        return new class implements EventSubscriberInterface
        {
            /**
             * @return array
             */
            public static function getSubscribedEvents(): array
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
    public function getEventDispatcher(ContainerInterface $container): EventDispatcherInterface
    {
        return $container->get(static::SERVICE_DISPATCHER);
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventDispatcher
     */
    public function createOldEventDispatcher()
    {
        return new SymfonyEventDispatcher();
    }
}
