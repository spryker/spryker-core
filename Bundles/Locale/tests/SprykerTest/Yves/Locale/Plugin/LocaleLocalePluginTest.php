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
use Spryker\Yves\Locale\Dependency\Client\LocaleToStoreClientInterface;
use Spryker\Yves\Locale\Plugin\Locale\LocaleLocalePlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Plugin
 * @group LocaleLocalePluginTest
 * Add your own group annotations below this line
 */
class LocaleLocalePluginTest extends Unit
{
    /**
     * @var string
     */
    protected const LOCALE = 'en_US';

    /**
     * @var string
     */
    protected const LOCALE_DE = 'de_DE';

    /**
     * @var string
     */
    protected const SERVICE_STORE = 'store';

    /**
     * @var string
     */
    protected const DEFAULT_STORE = 'DE';

    /**
     * @var string
     */
    protected const CURRENCY_EUR = 'EUR';

    /**
     * @var \Spryker\Yves\Locale\Plugin\Locale\LocaleLocalePlugin
     */
    protected $localeLocalePlugin;

    /**
     * @var string
     */
    protected $defaultLocaleName;

    /**
     * @var string
     */
    protected $notDefaultLocaleName;

    /**
     * @var string
     */
    protected $notDefaultLocaleIsoCode;

    /**
     * @var \Spryker\Yves\Kernel\Container
     */
    protected $container;

    /**
     * @var \SprykerTest\Yves\Locale\LocaleBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(LocaleDependencyProvider::LOCALE_CURRENT, static::LOCALE);

        $this->localeLocalePlugin = new LocaleLocalePlugin();

        $localeToStoreClientMock = $this->createLocaleToStoreClientMock();
        $mockedFactory = $this->tester->mockFactoryMethod('getStoreClient', $localeToStoreClientMock);
        $this->localeLocalePlugin->setFactory($mockedFactory);

        $localeClientMock = $this->createLocaleClientMock();
        $this->localeLocalePlugin->setClient($localeClientMock);
        $this->defaultLocaleName = $localeClientMock->getCurrentLocale();

        $availableLocales = $localeClientMock->getLocales();
        foreach ($availableLocales as $localeIsoCode => $localeName) {
            if ($localeName !== $this->defaultLocaleName) {
                $this->notDefaultLocaleIsoCode = $localeIsoCode;
                $this->notDefaultLocaleName = $localeName;

                break;
            }
        }

        $this->container = $this->tester->getContainer();
        $this->container->set(static::SERVICE_STORE, static::DEFAULT_STORE);
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
        $_SERVER['REQUEST_URI'] = "/$this->notDefaultLocaleIsoCode/";

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
        $_SERVER['REQUEST_URI'] = "/$this->notDefaultLocaleIsoCode/?gclid=1";

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
        $_SERVER['REQUEST_URI'] = "$this->notDefaultLocaleIsoCode";

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
        $_SERVER['REQUEST_URI'] = "$this->notDefaultLocaleIsoCode?gclid=1";

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
            ->willReturn([static::LOCALE, static::LOCALE_DE]);
        $localeClientMock->method('getCurrentLocale')
            ->willReturn(static::LOCALE);

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
                ->setName(static::DEFAULT_STORE)
                ->setAvailableLocaleIsoCodes([static::LOCALE]));

        return $localeToStoreClientMock;
    }
}
