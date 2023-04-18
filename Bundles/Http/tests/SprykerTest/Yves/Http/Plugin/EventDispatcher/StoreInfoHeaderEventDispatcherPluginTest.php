<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Http\Plugin\EventDispatcher;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\EventDispatcher\EventDispatcher;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Yves\Http\Dependency\Client\HttpToLocaleClientInterface;
use Spryker\Yves\Http\Dependency\Client\HttpToStoreClientInterface;
use Spryker\Yves\Http\HttpDependencyProvider;
use Spryker\Yves\Http\Plugin\EventDispatcher\EnvironmentInfoHeaderEventDispatcherPlugin;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Http
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
     * @var \SprykerTest\Yves\Http\HttpYvesTester
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
        $storeName = 'Spryker-Store';
        $httpToStoreClientMock = $this->getMockBuilder(HttpToStoreClientInterface::class)->onlyMethods(['getCurrentStore'])->getMock();

        $httpToStoreClientMock->method('getCurrentStore')
            ->willReturn((new StoreTransfer())->setName($storeName));

        $this->tester->setDependency(HttpDependencyProvider::CLIENT_STORE, $httpToStoreClientMock);

        $plugin = new EnvironmentInfoHeaderEventDispatcherPlugin();

        // Act
        $event = $this->dispatchEvent($plugin);

        // Assert
        $this->assertEquals($storeName, $event->getResponse()->headers->get(static::HEADER_X_STORE_NAME));
    }

    /**
     * @return void
     */
    public function testDispatchEventHandlesResponseWithHeaderXLocale(): void
    {
        // Arrange
        $locale = 'uk_UA';
        $httpToLocaleClientMock = $this->getMockBuilder(HttpToLocaleClientInterface::class)->onlyMethods(['getCurrentLocale'])->getMock();

        $httpToLocaleClientMock->method('getCurrentLocale')->willReturn($locale);

        $this->tester->setDependency(HttpDependencyProvider::CLIENT_LOCALE, $httpToLocaleClientMock);

        $plugin = new EnvironmentInfoHeaderEventDispatcherPlugin();

        // Act
        $event = $this->dispatchEvent($plugin);

        // Assert
        $response = $event->getResponse();
        $this->assertEquals($locale, $response->headers->get(static::HEADER_X_LOCALE_NAME));
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
}
