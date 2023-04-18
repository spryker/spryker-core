<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Http\Communication\Plugin\EventDispatcher;

use Codeception\Test\Unit;
use Spryker\Service\Container\Container;
use Spryker\Shared\EventDispatcher\EventDispatcher;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Zed\Http\Communication\Plugin\EventDispatcher\CacheControlHeaderEventDispatcherPlugin;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Http
 * @group Communication
 * @group Plugin
 * @group EventDispatcher
 * @group CacheControlHeaderEventDispatcherPluginTest
 * Add your own group annotations below this line
 */
class CacheControlHeaderEventDispatcherPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const CACHE_CONTROL_DIRECTIVE_MAX_AGE = 'max-age';

    /**
     * @var string
     */
    protected const CACHE_CONTROL_DIRECTIVE_S_MAX_AGE = 's-maxage';

    /**
     * @var string
     */
    protected const CACHE_CONTROL_DIRECTIVE_NO_CACHE = 'no-cache';

    /**
     * @var string
     */
    protected const CACHE_CONTROL_DIRECTIVE_MUST_REVALIDATE = 'must-revalidate';

    /**
     * @var string
     */
    protected const CACHE_CONTROL_DIRECTIVE_NO_STORE = 'no-store';

    /**
     * @var string
     */
    protected const CACHE_CONTROL_DIRECTIVE_PUBLIC = 'public';

    /**
     * @var string
     */
    protected const CACHE_CONTROL_DIRECTIVE_PRIVATE = 'private';

    /**
     * @var string
     */
    protected const CACHE_CONTROL_DIRECTIVE_NO_TRANSFORM = 'no-transform';

    /**
     * @var string
     */
    protected const CACHE_CONTROL_DIRECTIVE_IMMUTABLE = 'immutable';

    /**
     * @var string
     */
    protected const CACHE_CONTROL_STALE_WHILE_REVALIDATE = 'stale-while-revalidate';

    /**
     * @var \SprykerTest\Zed\Http\HttpCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testDispatchEventHandleResponseDisablePluginAndDefaultCacheControlHeader(): void
    {
        // Arrange
        $plugin = new CacheControlHeaderEventDispatcherPlugin();
        $plugin->setConfig($this->tester->getModuleConfig());

        // Act
        $event = $this->dispatchEvent($plugin);

        // Assert
        $this->assertTrue($event->getResponse()->headers->hasCacheControlDirective('private'));
        $this->assertTrue($event->getResponse()->headers->hasCacheControlDirective('no-cache'));
    }

    /**
     * @return void
     */
    public function testDispatchEventHandleResponseEnablePluginWithDefaultCacheControlHeader(): void
    {
        // Arrange
        $this->tester->mockConfigMethod('getCacheControlConfig', []);

        $plugin = new CacheControlHeaderEventDispatcherPlugin();
        $plugin->setConfig($this->tester->getModuleConfig());

        // Act
        $event = $this->dispatchEvent($plugin);

        // Assert
        $this->assertTrue($event->getResponse()->headers->hasCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_PRIVATE));
        $this->assertTrue($event->getResponse()->headers->hasCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_NO_CACHE));
    }

    /**
     * @return void
     */
    public function testDispatchEventHandleResponseWithPrivateDirective(): void
    {
        // Arrange
        $this->tester->mockConfigMethod('getCacheControlConfig', [
            static::CACHE_CONTROL_DIRECTIVE_PRIVATE => true,
        ]);

        $plugin = new CacheControlHeaderEventDispatcherPlugin();
        $plugin->setConfig($this->tester->getModuleConfig());

        // Act
        $event = $this->dispatchEvent($plugin);

        // Assert
        $this->assertSame(
            static::CACHE_CONTROL_DIRECTIVE_PRIVATE,
            $event->getResponse()->headers->get('Cache-Control'),
        );
    }

    /**
     * @return void
     */
    public function testDispatchEventHandleResponseWithPublicDirective(): void
    {
        // Arrange
        $this->tester->mockConfigMethod(
            'getCacheControlConfig',
            [
                static::CACHE_CONTROL_DIRECTIVE_PUBLIC => true,
            ],
        );

        $plugin = new CacheControlHeaderEventDispatcherPlugin();
        $plugin->setConfig($this->tester->getModuleConfig());

        // Act
        $event = $this->dispatchEvent($plugin);

        // Assert
        $this->assertTrue($event->getResponse()->headers->hasCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_PUBLIC));
    }

    /**
     * @return void
     */
    public function testDispatchEventHandleResponseWithPrivateAndPublicDirective(): void
    {
        // Arrange
        $this->tester->mockConfigMethod(
            'getCacheControlConfig',
            [
                static::CACHE_CONTROL_DIRECTIVE_PUBLIC => true,
                static::CACHE_CONTROL_DIRECTIVE_PRIVATE => true,
            ],
        );

        $plugin = new CacheControlHeaderEventDispatcherPlugin();
        $plugin->setConfig($this->tester->getModuleConfig());

        // Act
        $event = $this->dispatchEvent($plugin);

        // Assert
        $this->assertSame(
            static::CACHE_CONTROL_DIRECTIVE_PRIVATE,
            $event->getResponse()->headers->get('Cache-Control'),
        );
        $this->assertFalse($event->getResponse()->headers->hasCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_PUBLIC));
        $this->assertTrue($event->getResponse()->headers->hasCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_PRIVATE));
    }

    /**
     * @return void
     */
    public function testDispatchEventHandleResponseWithPublicAndNoCacheDirective(): void
    {
        // Arrange
        $this->tester->mockConfigMethod(
            'getCacheControlConfig',
            [
                static::CACHE_CONTROL_DIRECTIVE_PUBLIC => true,
                static::CACHE_CONTROL_DIRECTIVE_NO_CACHE => true,
            ],
        );

        $plugin = new CacheControlHeaderEventDispatcherPlugin();
        $plugin->setConfig($this->tester->getModuleConfig());

        // Act
        $event = $this->dispatchEvent($plugin);

        // Assert
        $this->assertTrue($event->getResponse()->headers->hasCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_PUBLIC));
        $this->assertTrue($event->getResponse()->headers->hasCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_NO_CACHE));
    }

    /**
     * @return void
     */
    public function testDispatchEventHandleResponseWithMaxAgeDirective(): void
    {
        // Arrange
        $this->tester->mockConfigMethod(
            'getCacheControlConfig',
            [
                static::CACHE_CONTROL_DIRECTIVE_MAX_AGE => 100,
            ],
        );

        $plugin = new CacheControlHeaderEventDispatcherPlugin();
        $plugin->setConfig($this->tester->getModuleConfig());

        // Act
        $event = $this->dispatchEvent($plugin);

        // Assert
        $this->assertTrue($event->getResponse()->headers->hasCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_PRIVATE));
        $this->assertTrue($event->getResponse()->headers->hasCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_MAX_AGE));
        $this->assertSame('100', $event->getResponse()->headers->getCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_MAX_AGE));
    }

    /**
     * @return void
     */
    public function testDispatchEventHandleResponseWithSMaxAgeDirective(): void
    {
        // Arrange
        $this->tester->mockConfigMethod(
            'getCacheControlConfig',
            [
                static::CACHE_CONTROL_DIRECTIVE_S_MAX_AGE => 100,
            ],
        );

        $plugin = new CacheControlHeaderEventDispatcherPlugin();
        $plugin->setConfig($this->tester->getModuleConfig());

        // Act
        $event = $this->dispatchEvent($plugin);

        // Assert
        $this->assertTrue($event->getResponse()->headers->hasCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_PUBLIC));
        $this->assertFalse($event->getResponse()->headers->hasCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_PRIVATE));
        $this->assertTrue($event->getResponse()->headers->hasCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_S_MAX_AGE));
        $this->assertSame('100', $event->getResponse()->headers->getCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_S_MAX_AGE));
    }

    /**
     * @return void
     */
    public function testDispatchEventHandleResponseWithMustRevalidateDirective(): void
    {
        // Arrange
        $this->tester->mockConfigMethod(
            'getCacheControlConfig',
            [
                static::CACHE_CONTROL_DIRECTIVE_MUST_REVALIDATE => true,
            ],
        );

        $plugin = new CacheControlHeaderEventDispatcherPlugin();
        $plugin->setConfig($this->tester->getModuleConfig());

        // Act
        $event = $this->dispatchEvent($plugin);

        // Assert
        $this->assertFalse($event->getResponse()->headers->hasCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_PUBLIC));
        $this->assertTrue($event->getResponse()->headers->hasCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_PRIVATE));
        $this->assertTrue($event->getResponse()->headers->hasCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_MUST_REVALIDATE));
    }

    /**
     * @return void
     */
    public function testDispatchEventHandleResponseWithNoCacheDirective(): void
    {
        // Arrange
        $this->tester->mockConfigMethod(
            'getCacheControlConfig',
            [
                static::CACHE_CONTROL_DIRECTIVE_NO_CACHE => true,
            ],
        );

        $plugin = new CacheControlHeaderEventDispatcherPlugin();
        $plugin->setConfig($this->tester->getModuleConfig());

        // Act
        $event = $this->dispatchEvent($plugin);

        // Assert
        $this->assertFalse($event->getResponse()->headers->hasCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_PUBLIC));
        $this->assertTrue($event->getResponse()->headers->hasCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_PRIVATE));
        $this->assertTrue($event->getResponse()->headers->hasCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_NO_CACHE));
    }

    /**
     * @return void
     */
    public function testDispatchEventHandleResponseWithNoStoreDirective(): void
    {
        // Arrange
        $this->tester->mockConfigMethod(
            'getCacheControlConfig',
            [
                static::CACHE_CONTROL_DIRECTIVE_NO_STORE => true,
            ],
        );

        $plugin = new CacheControlHeaderEventDispatcherPlugin();
        $plugin->setConfig($this->tester->getModuleConfig());

        // Act
        $event = $this->dispatchEvent($plugin);

        // Assert
        $this->assertFalse($event->getResponse()->headers->hasCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_PUBLIC));
        $this->assertTrue($event->getResponse()->headers->hasCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_PRIVATE));
        $this->assertTrue($event->getResponse()->headers->hasCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_NO_STORE));
    }

    /**
     * @return void
     */
    public function testDispatchEventHandleResponseWithNoTransformDirective(): void
    {
        // Arrange
        $this->tester->mockConfigMethod(
            'getCacheControlConfig',
            [
                static::CACHE_CONTROL_DIRECTIVE_NO_TRANSFORM => true,
            ],
        );

        $plugin = new CacheControlHeaderEventDispatcherPlugin();
        $plugin->setConfig($this->tester->getModuleConfig());

        // Act
        $event = $this->dispatchEvent($plugin);

        // Assert
        $this->assertFalse($event->getResponse()->headers->hasCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_PUBLIC));
        $this->assertTrue($event->getResponse()->headers->hasCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_PRIVATE));
        $this->assertTrue($event->getResponse()->headers->hasCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_NO_TRANSFORM));
    }

    /**
     * @return void
     */
    public function testDispatchEventHandleResponseWithImmutableDirective(): void
    {
        // Arrange
        $this->tester->mockConfigMethod(
            'getCacheControlConfig',
            [
                static::CACHE_CONTROL_DIRECTIVE_IMMUTABLE => true,
            ],
        );

        $plugin = new CacheControlHeaderEventDispatcherPlugin();
        $plugin->setConfig($this->tester->getModuleConfig());

        // Act
        $event = $this->dispatchEvent($plugin);

        // Assert
        $this->assertFalse($event->getResponse()->headers->hasCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_PUBLIC));
        $this->assertTrue($event->getResponse()->headers->hasCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_PRIVATE));
        $this->assertTrue($event->getResponse()->headers->hasCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_IMMUTABLE));
    }

    /**
     * @return void
     */
    public function testDispatchEventHandleResponseWithStaleWileRevalidateDirective(): void
    {
        // Arrange
        $this->tester->mockConfigMethod(
            'getCacheControlConfig',
            [
                static::CACHE_CONTROL_STALE_WHILE_REVALIDATE => true,
            ],
        );

        $plugin = new CacheControlHeaderEventDispatcherPlugin();
        $plugin->setConfig($this->tester->getModuleConfig());

        // Act
        $event = $this->dispatchEvent($plugin);

        // Assert
        $this->assertFalse($event->getResponse()->headers->hasCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_PUBLIC));
        $this->assertTrue($event->getResponse()->headers->hasCacheControlDirective(static::CACHE_CONTROL_DIRECTIVE_PRIVATE));
        $this->assertTrue($event->getResponse()->headers->hasCacheControlDirective(static::CACHE_CONTROL_STALE_WHILE_REVALIDATE));
    }

    /**
     * @param \Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface $plugin
     *
     * @return \Symfony\Component\HttpKernel\Event\ResponseEvent
     */
    protected function dispatchEvent(EventDispatcherPluginInterface $plugin): ResponseEvent
    {
        $eventDispatcher = new EventDispatcher();
        $container = new Container();
        $plugin->extend($eventDispatcher, $container);

        /** @var \Symfony\Component\HttpKernel\Event\ResponseEvent $event */
        $event = $eventDispatcher->dispatch($this->tester->getResponseEvent(), KernelEvents::RESPONSE);

        return $event;
    }
}
