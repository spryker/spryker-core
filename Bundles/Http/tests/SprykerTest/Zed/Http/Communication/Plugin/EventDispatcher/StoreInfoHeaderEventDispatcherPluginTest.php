<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Http\Communication\Plugin\EventDispatcher;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\EventDispatcher\EventDispatcher;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Zed\Http\Communication\Plugin\EventDispatcher\EnvironmentInfoHeaderEventDispatcherPlugin;
use Spryker\Zed\Http\Dependency\Facade\HttpToLocaleFacadeInterface;
use Spryker\Zed\Http\Dependency\Facade\HttpToStoreFacadeInterface;
use Spryker\Zed\Http\HttpDependencyProvider;
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
 * @group StoreInfoHeaderEventDispatcherPluginTest
 * Add your own group annotations below this line
 */
class StoreInfoHeaderEventDispatcherPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const HEADER_X_CODE_BUCKET_NAME = 'X-CodeBucket';

    /**
     * @var string
     */
    protected const HEADER_X_STORE_NAME = 'X-Store';

    /**
     * @var string
     */
    protected const HEADER_X_ENV_NAME = 'X-Env';

    /**
     * @var string
     */
    protected const HEADER_X_LOCALE_NAME = 'X-Locale';

    /**
     * @var string
     */
    protected const DEFAULT_LOCALE = 'uk_UA';

    /**
     * @var string
     */
    protected const DEFAULT_STORE = 'UA';

    /**
     * @var \SprykerTest\Zed\Http\HttpCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testDispatchEventHandlesResponseWithHeaderXCodeBucket(): void
    {
        // Arrange
        $plugin = new EnvironmentInfoHeaderEventDispatcherPlugin();

        // Act
        $event = $this->dispatchEvent($plugin);

        // Assert
        $response = $event->getResponse();
        $this->assertEquals(APPLICATION_CODE_BUCKET, $response->headers->get(static::HEADER_X_CODE_BUCKET_NAME));
    }

    /**
     * @return void
     */
    public function testDispatchEventHandlesResponseWithHeaderXEnv(): void
    {
        // Arrange
        $plugin = new EnvironmentInfoHeaderEventDispatcherPlugin();

        // Act
        $event = $this->dispatchEvent($plugin);

        // Assert
        $this->assertEquals(APPLICATION_ENV, $event->getResponse()->headers->get(static::HEADER_X_ENV_NAME));
    }

    /**
     * @return void
     */
    public function testDispatchEventHandlesResponseWithHeaderXStore(): void
    {
        // Arrange
        $this->setLocaleDependency();
        $this->setStoreDependency();

        $plugin = new EnvironmentInfoHeaderEventDispatcherPlugin();

        // Act
        $event = $this->dispatchEvent($plugin);

        // Assert
        $this->assertEquals(static::DEFAULT_STORE, $event->getResponse()->headers->get(static::HEADER_X_STORE_NAME));
    }

    /**
     * @return void
     */
    public function testDispatchEventHandlesResponseWithHeaderXLocale(): void
    {
        // Arrange
        $this->setLocaleDependency();

        $plugin = new EnvironmentInfoHeaderEventDispatcherPlugin();

        // Act
        $event = $this->dispatchEvent($plugin);

        // Assert
        $this->assertEquals(static::DEFAULT_LOCALE, $event->getResponse()->headers->get(static::HEADER_X_LOCALE_NAME));
    }

    /**
     * @param \Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface $plugin
     *
     * @return \Symfony\Component\HttpKernel\Event\ResponseEvent
     */
    protected function dispatchEvent(EventDispatcherPluginInterface $plugin): ResponseEvent
    {
        $eventDispatcher = new EventDispatcher();
        $plugin->extend($eventDispatcher, $this->tester->getContainer());

        /** @var \Symfony\Component\HttpKernel\Event\ResponseEvent $event */
        $event = $eventDispatcher->dispatch($this->tester->getResponseEvent(), KernelEvents::RESPONSE);

        return $event;
    }

    /**
     * @return void
     */
    protected function setLocaleDependency(): void
    {
        $httpToLocaleFacadeMock = $this->getMockBuilder(HttpToLocaleFacadeInterface::class)->onlyMethods(['getCurrentLocale'])->getMock();

        $httpToLocaleFacadeMock->method('getCurrentLocale')->willReturn((new LocaleTransfer())->setLocaleName(static::DEFAULT_LOCALE));

        $this->tester->setDependency(HttpDependencyProvider::FACADE_LOCALE, $httpToLocaleFacadeMock);
    }

    /**
     * @return void
     */
    protected function setStoreDependency(): void
    {
        $httpToStoreFacadeMock = $this->getMockBuilder(HttpToStoreFacadeInterface::class)->onlyMethods(['getCurrentStore', 'isDynamicStoreEnabled'])->getMock();

        $httpToStoreFacadeMock->method('getCurrentStore')
            ->willReturn((new StoreTransfer())->setName(static::DEFAULT_STORE));

        $this->tester->setDependency(HttpDependencyProvider::FACADE_STORE, $httpToStoreFacadeMock);
    }
}
