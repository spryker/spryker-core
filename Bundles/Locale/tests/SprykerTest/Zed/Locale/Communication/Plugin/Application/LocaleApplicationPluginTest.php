<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Locale\Communication\Plugin\Application;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Shared\Kernel\Container\ContainerProxy;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\LocaleExtension\Dependency\Plugin\LocalePluginInterface;
use Spryker\Zed\Locale\Communication\LocaleCommunicationFactory;
use Spryker\Zed\Locale\Communication\Plugin\Application\LocaleApplicationPlugin;
use Spryker\Zed\Locale\Dependency\Facade\LocaleToStoreFacadeInterface;
use SprykerTest\Zed\Locale\LocaleCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Locale
 * @group Communication
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
     * @var \SprykerTest\Zed\Locale\LocaleCommunicationTester
     */
    protected LocaleCommunicationTester $tester;

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
        $localeToStoreClientMock = $this->createMock(LocaleToStoreFacadeInterface::class);
        $localeToStoreClientMock->method('isDynamicStoreEnabled')->willReturn(false);

        $localePluginMock = $this->createMock(LocalePluginInterface::class);
        $localePluginMock->method('getLocaleTransfer')->willReturn((new LocaleTransfer())->setLocaleName($this->tester::LOCALE));

        $storeMock = $this->createMock(Store::class);
        $storeMock->method('setCurrentLocale')->with($this->tester::LOCALE);

        $mockedFactory = $this->createMock(LocaleCommunicationFactory::class);
        $mockedFactory->method('getStoreFacade')->willReturn($localeToStoreClientMock);
        $mockedFactory->method('getLocalePlugin')->willReturn($localePluginMock);
        $mockedFactory->method('getStore')->willReturn($storeMock);

        $localeApplicationPlugin = new LocaleApplicationPlugin();
        $localeApplicationPlugin->setFactory($mockedFactory);
         $localeApplicationPlugin->provide($this->container);

        // Act
        $locale = $this->container->get($this->tester::SERVICE_LOCALE);

        // Assert
        $this->assertSame($locale, $this->tester::LOCALE);
        $this->assertSame($this->container->get($this->tester::BC_FEATURE_FLAG_LOCALE_LISTENER), false);
    }

    /**
     * @return void
     */
    public function testProvidesLocaleWhenDynamicStoreEnabled(): void
    {
        // Arrange
        $localeToStoreClientMock = $this->createMock(LocaleToStoreFacadeInterface::class);
        $localeToStoreClientMock->method('isDynamicStoreEnabled')->willReturn(true);

        $localePluginMock = $this->createMock(LocalePluginInterface::class);
        $localePluginMock->method('getLocaleTransfer')->willReturn((new LocaleTransfer())->setLocaleName($this->tester::LOCALE));

        $storeMock = $this->createMock(Store::class);
        $storeMock->expects($this->never())->method('setCurrentLocale');

        $mockedFactory = $this->createMock(LocaleCommunicationFactory::class);
        $mockedFactory->method('getStoreFacade')->willReturn($localeToStoreClientMock);
        $mockedFactory->method('getLocalePlugin')->willReturn($localePluginMock);
        $mockedFactory->method('getStore')->willReturn($storeMock);

        $localeApplicationPlugin = new LocaleApplicationPlugin();
        $localeApplicationPlugin->setFactory($mockedFactory);
        $localeApplicationPlugin->provide($this->container);

        // Act
        $locale = $this->container->get($this->tester::SERVICE_LOCALE);

        // Assert
        $this->assertSame($locale, $this->tester::LOCALE);
        $this->assertSame($this->container->get($this->tester::BC_FEATURE_FLAG_LOCALE_LISTENER), false);
    }
}
