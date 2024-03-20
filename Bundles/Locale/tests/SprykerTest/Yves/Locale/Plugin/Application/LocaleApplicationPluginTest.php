<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Locale\Plugin\Application;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Shared\Kernel\Container\ContainerProxy;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\LocaleExtension\Dependency\Plugin\LocalePluginInterface;
use Spryker\Yves\Locale\Dependency\Client\LocaleToStoreClientInterface;
use Spryker\Yves\Locale\Plugin\Application\LocaleApplicationPlugin;
use SprykerTest\Yves\Locale\LocaleBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Locale
 * @group Plugin
 * @group Application
 * @group LocaleApplicationPluginTest
 * Add your own group annotations below this line
 */
class LocaleApplicationPluginTest extends Unit
{
    /**
     * @var \Spryker\Shared\Kernel\Container\ContainerProxy
     */
    protected ContainerProxy $container;

    /**
     * @var \SprykerTest\Yves\Locale\LocaleBusinessTester
     */
    protected LocaleBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->container = $this->tester->getContainer();
    }

    /**
     * @return void
     */
    public function testProvidesLocale(): void
    {
        // Arrange
        $localeToStoreClientMock = $this->createMock(LocaleToStoreClientInterface::class);
        $localeToStoreClientMock->method('isDynamicStoreEnabled')->willReturn(false);

        $localePluginMock = $this->createMock(LocalePluginInterface::class);
        $localePluginMock->method('getLocaleTransfer')->willReturn((new LocaleTransfer())->setLocaleName($this->tester::LOCALE_DE));

        $storeMock = $this->createMock(Store::class);
        $storeMock->method('setCurrentLocale')->with($this->tester::LOCALE_DE);

        $this->tester->mockFactoryMethod('getStoreClient', $localeToStoreClientMock);
        $this->tester->mockFactoryMethod('getLocalePlugin', $localePluginMock);
        $this->tester->mockFactoryMethod('getStore', $storeMock);

        $localeApplicationPlugin = new LocaleApplicationPlugin();
        $localeApplicationPlugin->setFactory($this->tester->getFactory());
        $localeApplicationPlugin->provide($this->container);

        // Act
        $locale = $this->container->get($this->tester::SERVICE_LOCALE);

        // Assert
        $this->assertSame($locale, $this->tester::LOCALE_DE);
        $this->assertSame($this->container->get($this->tester::BC_FEATURE_FLAG_LOCALE_LISTENER), false);
    }

    /**
     * @return void
     */
    public function testProvidesLocaleWhenDynamicStoreEnabled(): void
    {
        // Arrange
        $localeToStoreClientMock = $this->createMock(LocaleToStoreClientInterface::class);
        $localeToStoreClientMock->method('isDynamicStoreEnabled')->willReturn(true);

        $localePluginMock = $this->createMock(LocalePluginInterface::class);
        $localePluginMock->method('getLocaleTransfer')->willReturn((new LocaleTransfer())->setLocaleName($this->tester::LOCALE_DE));

        $storeMock = $this->createMock(Store::class);
        $storeMock->expects($this->never())->method('setCurrentLocale');

        $this->tester->mockFactoryMethod('getStoreClient', $localeToStoreClientMock);
        $this->tester->mockFactoryMethod('getLocalePlugin', $localePluginMock);
        $this->tester->mockFactoryMethod('getStore', $storeMock);

        $localeApplicationPlugin = new LocaleApplicationPlugin();
        $localeApplicationPlugin->setFactory($this->tester->getFactory());
        $localeApplicationPlugin->provide($this->container);

        // Act
        $locale = $this->container->get($this->tester::SERVICE_LOCALE);

        // Assert
        $this->assertSame($locale, $this->tester::LOCALE_DE);
        $this->assertSame($this->container->get($this->tester::BC_FEATURE_FLAG_LOCALE_LISTENER), false);
    }
}
