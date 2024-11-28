<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\Locale\LocaleClient;
use Spryker\Client\Locale\LocaleClientInterface;
use Spryker\Client\Locale\LocaleDependencyProvider;
use Spryker\Shared\Kernel\Container\ContainerProxy;
use Spryker\Yves\Locale\Dependency\Client\LocaleToStoreClientInterface;
use Spryker\Yves\Locale\LocaleConfig;
use Spryker\Yves\Locale\Plugin\Locale\LocaleLocalePlugin;
use SprykerTest\Yves\Locale\LocaleBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Plugin
 * @group LocaleLocalePluginDynamicStoreTest
 * Add your own group annotations below this line
 */
class LocaleLocalePluginDynamicStoreTest extends Unit
{
    /**
     * @var \Spryker\Yves\Locale\Plugin\Locale\LocaleLocalePlugin
     */
    protected LocaleLocalePlugin $localeLocalePlugin;

    /**
     * @var string
     */
    protected string $defaultLocaleName;

    /**
     * @var string
     */
    protected string $notDefaultLocaleName;

    /**
     * @var string
     */
    protected string $notDefaultLocaleIsoCode;

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

        $this->tester->setDependency(LocaleDependencyProvider::LOCALE_CURRENT, $this->tester::LOCALE);

        $this->localeLocalePlugin = new LocaleLocalePlugin();

        $localeToStoreClientMock = $this->createLocaleToStoreClientMock();
        $localeToStoreClientMock->method('isDynamicStoreEnabled')->willReturn(true);
        $localeToStoreClientMock->method('getStoreByName')->willReturn(
            (new StoreTransfer())->setDefaultLocaleIsoCode($this->tester::LOCALE),
        );
        $mockedFactory = $this->tester->mockFactoryMethod('getStoreClient', $localeToStoreClientMock);
        $this->localeLocalePlugin->setFactory($mockedFactory);

        $localeClientMock = $this->createLocaleClientMock();
        $this->localeLocalePlugin->setClient($localeClientMock);
        $this->defaultLocaleName = $localeClientMock->getCurrentLocale();

        $localeConfigMock = $this->createMock(LocaleConfig::class);
        $localeConfigMock->method('isStoreRoutingEnabled')->willReturn(true);
        $localeConfigMock->method('getLocaleCodeIndex')->willReturn(1);
        $this->localeLocalePlugin->setConfig($localeConfigMock);

        $availableLocales = $localeClientMock->getLocales();
        foreach ($availableLocales as $localeIsoCode => $localeName) {
            if ($localeName !== $this->defaultLocaleName) {
                $this->notDefaultLocaleIsoCode = $localeIsoCode;
                $this->notDefaultLocaleName = $localeName;

                break;
            }
        }

        $this->container = $this->tester->getContainer();
        $this->container->set($this->tester::SERVICE_STORE, $this->tester::DEFAULT_STORE);
    }

    /**
     * @return void
     */
    public function testPluginReturnsDefaultLocaleByDefault(): void
    {
        // Act
        $localeTransfer = $this->localeLocalePlugin->getLocaleTransfer($this->container);

        // Assert
        $this->assertSame($localeTransfer->getLocaleName(), $this->defaultLocaleName);
    }

    /**
     * @return void
     */
    public function testPluginReturnsLocaleSpecifiedInUrlWithSlashes(): void
    {
        // Arrange
        $_SERVER['REQUEST_URI'] = "/DE/$this->notDefaultLocaleIsoCode/";

        // Act
        $localeTransfer = $this->localeLocalePlugin->getLocaleTransfer($this->container);

        // Assert
        $this->assertSame($localeTransfer->getLocaleName(), $this->notDefaultLocaleName);
    }

    /**
     * @return void
     */
    public function testPluginReturnsLocaleSpecifiedInUrlWithSlashesWithQueryString(): void
    {
        // Arrange
        $_SERVER['REQUEST_URI'] = "/DE/$this->notDefaultLocaleIsoCode/?gclid=1";

        // Act
        $localeTransfer = $this->localeLocalePlugin->getLocaleTransfer($this->container);

        // Assert
        $this->assertSame($localeTransfer->getLocaleName(), $this->notDefaultLocaleName);
    }

    /**
     * @return void
     */
    public function testPluginReturnsLocaleSpecifiedInUrlWithoutSlashes(): void
    {
        // Arrange
        $_SERVER['REQUEST_URI'] = "/DE/$this->notDefaultLocaleIsoCode";

        // Act
        $localeTransfer = $this->localeLocalePlugin->getLocaleTransfer($this->container);

        // Assert
        $this->assertSame($localeTransfer->getLocaleName(), $this->notDefaultLocaleName);
    }

    /**
     * @return void
     */
    public function testPluginReturnsLocaleSpecifiedInUrlWithoutSlashesWithQueryString(): void
    {
        // Arrange
        $_SERVER['REQUEST_URI'] = "/DE/$this->notDefaultLocaleIsoCode?gclid=1";

        // Act
        $localeTransfer = $this->localeLocalePlugin->getLocaleTransfer($this->container);

        // Assert
        $this->assertSame($localeTransfer->getLocaleName(), $this->notDefaultLocaleName);
    }

    /**
     * @return void
     */
    public function testPluginReturnsDefaultLocaleWhenUrlContainsNotExistingLocale(): void
    {
        // Arrange
        $_SERVER['REQUEST_URI'] = 'YY';

        // Act
        $localeTransfer = $this->localeLocalePlugin->getLocaleTransfer($this->container);

        // Assert
        $this->assertSame($localeTransfer->getLocaleName(), $this->defaultLocaleName);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerTest\Yves\Plugin\Spryker\Client\Locale\LocaleClientInterface
     */
    protected function createLocaleClientMock(): LocaleClientInterface
    {
        $localeClientMock = $this->createMock(LocaleClient::class);
        $localeClientMock->method('getLocales')
            ->willReturn([$this->tester::LOCALE, $this->tester::LOCALE_DE]);
        $localeClientMock->method('getCurrentLocale')
            ->willReturn($this->tester::LOCALE);

        return $localeClientMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerTest\Yves\Plugin\Spryker\Yves\Locale\Dependency\Client\LocaleToStoreClientInterface
     */
    protected function createLocaleToStoreClientMock(): LocaleToStoreClientInterface
    {
        $localeToStoreClientMock = $this->createMock(LocaleToStoreClientInterface::class);
        $localeToStoreClientMock->method('getCurrentStore')
            ->willReturn((new StoreTransfer())
                ->setName($this->tester::DEFAULT_STORE)
                ->setAvailableLocaleIsoCodes([$this->tester::LOCALE]));

        return $localeToStoreClientMock;
    }
}
