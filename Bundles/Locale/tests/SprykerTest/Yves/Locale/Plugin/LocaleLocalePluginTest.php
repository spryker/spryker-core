<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Plugin;

use Codeception\Test\Unit;
use Spryker\Client\Locale\LocaleClient;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Locale\LocaleFactory;
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
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->localeLocalePlugin = new LocaleLocalePlugin();
        $this->defaultLocaleName = (new LocaleClient())->getCurrentLocale();

        $availableLocales = (new LocaleFactory())->getStore()->getLocales();
        foreach ($availableLocales as $localeIsoCode => $localeName) {
            if ($localeName !== $this->defaultLocaleName) {
                $this->notDefaultLocaleIsoCode = $localeIsoCode;
                $this->notDefaultLocaleName = $localeName;

                break;
            }
        }
    }

    /**
     * @return void
     */
    public function testPluginReturnsDefaultLocaleByDefault(): void
    {
        // Act
        $localeTransfer = $this->localeLocalePlugin->getLocaleTransfer(new Container());

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
        $localeTransfer = $this->localeLocalePlugin->getLocaleTransfer(new Container());

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
        $localeTransfer = $this->localeLocalePlugin->getLocaleTransfer(new Container());

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
        $localeTransfer = $this->localeLocalePlugin->getLocaleTransfer(new Container());

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
        $localeTransfer = $this->localeLocalePlugin->getLocaleTransfer(new Container());

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
        $localeTransfer = $this->localeLocalePlugin->getLocaleTransfer(new Container());

        // Assert
        $this->assertSame($localeTransfer->getLocaleName(), $this->defaultLocaleName);
    }
}
