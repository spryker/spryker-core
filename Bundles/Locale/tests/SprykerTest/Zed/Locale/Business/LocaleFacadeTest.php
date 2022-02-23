<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Locale\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Locale\Persistence\LocaleQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Locale
 * @group Business
 * @group Facade
 * @group LocaleFacadeTest
 * Add your own group annotations below this line
 */
class LocaleFacadeTest extends Unit
{
    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacade
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface
     */
    protected $localeQueryContainer;

    /**
     * @var array<string>
     */
    protected $availableLocales = [];

    /**
     * @var array<string>
     */
    protected $localeNames = [];

    /**
     * @var \SprykerTest\Zed\Locale\LocaleBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->localeFacade = new LocaleFacade();
        $this->localeQueryContainer = new LocaleQueryContainer();
        $this->availableLocales = Store::getInstance()->getLocales();
        $this->localeNames = $this->localeFacade->getAvailableLocales();
    }

    /**
     * @return void
     */
    public function testAvailableLocalesToBeArrayType(): void
    {
        $this->assertIsArray($this->localeNames);
    }

    /**
     * @return void
     */
    public function testAvailableLocalesAreTheSameAsConfiguredOnes(): void
    {
        $this->assertSame(
            array_values($this->availableLocales),
            array_values($this->localeNames),
        );
    }

    /**
     * @return void
     */
    public function testAvailableLocalesHasDifferentIdsThanConfiguredOnes(): void
    {
        $this->assertNotSame(
            array_keys($this->availableLocales),
            array_keys($this->localeNames),
        );
    }

    /**
     * @return void
     */
    public function testCurrentLocaleIsChangeable(): void
    {
        //Arrange
        $currentLocale = $this->localeFacade->getCurrentLocale();
        $newLocale = (new LocaleTransfer())->setLocaleName('de_DE');

        //Act
        $this->localeFacade->setCurrentLocale($newLocale);
        $newCurrentLocale = $this->localeFacade->getCurrentLocale();

        //Assert
        $this->assertSame($currentLocale->getLocaleName(), $newCurrentLocale->getLocaleName());
    }

    /**
     * @return void
     */
    public function testGetLocaleCollectionGetsLocalesFromStoreInstance(): void
    {
        // Act
        $localeTransfers = $this->localeFacade->getLocaleCollection();

        // Assert
        $this->assertSame('en_US', array_keys($localeTransfers)[0]);
        $this->assertSame('de_DE', array_keys($localeTransfers)[1]);
    }
}
