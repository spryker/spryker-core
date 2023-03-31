<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Locale\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleConditionsTransfer;
use Generated\Shared\Transfer\LocaleCriteriaTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Locale\Business\LocaleFacade;

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
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const STORE_NAME_US = 'US';

    /**
     * @var string
     */
    protected const LOCALE_EN = 'en_US';

    /**
     * @var string
     */
    protected const LOCALE_DE = 'de_DE';

    /**
     * @var string
     */
    protected const LOCALE_FR = 'fr_FR';

    /**
     * @var array<string, string>
     */
    protected const BACKOFFICE_UI_LOCALES = [
        self::LOCALE_EN,
        self::LOCALE_DE,
    ];

    /**
     * @uses \Spryker\Zed\Locale\Business\Reader\LocaleReader::ERROR_MESSAGE_EMPTY_DEFAULT_LOCALE
     *
     * @var string
     */
    protected const MESSAGE_DEFAULT_LOCALE_MUST_NOT_BE_EMPTY = 'Default locale must not be empty.';

    /**
     * @uses \Spryker\Zed\Locale\Business\Reader\LocaleReader::ERROR_MESSAGE_INVALID_DEFAULT_LOCALE
     *
     * @var string
     */
    protected const MESSAGE_DEFAULT_LOCALE_MUST_BE_PRESENTED_IN_AVAILABLE_LOCALES = 'Default locale must be presented in available locales.';

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacade
     */
    protected $localeFacade;

    /**
     * @var array
     */
    protected $availableLocales = [];

    /**
     * @var array
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

        $localeCriteriaTransfer = (new LocaleCriteriaTransfer())
            ->setLocaleConditions((new LocaleConditionsTransfer())
                ->setAssignedToStore(true));
        foreach ($this->localeFacade->getLocaleCollection($localeCriteriaTransfer) as $localeTransfer) {
            $this->localeNames[$localeTransfer->getIdLocaleOrFail()] = $localeTransfer->getLocaleNameOrFail();
        }
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
    public function testUpdateStoreLocalesWithAddingNewAndRemovingOldRelations(): void
    {
        if ($this->tester->isDynamicStoreEnabled() === false) {
            $this->markTestSkipped('Test is valid for Dynamic Store on-mode only.');
        }

        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->tester->deleteLocaleStore($storeTransfer->getIdStoreOrFail());

        $idLocaleDe = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_DE])->getIdLocaleOrFail();
        $idLocaleUs = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_EN])->getIdLocaleOrFail();
        $idLocaleFr = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_FR])->getIdLocaleOrFail();

        $this->tester->haveLocaleStore($storeTransfer->getIdStoreOrFail(), $idLocaleDe);
        $this->tester->haveLocaleStore($storeTransfer->getIdStoreOrFail(), $idLocaleFr);

        $storeTransfer->setAvailableLocaleIsoCodes([static::LOCALE_DE, static::LOCALE_EN]);

        // Act
        $storeResponseTransfer = $this->localeFacade->updateStoreLocales($storeTransfer);

        // Assert
        $this->assertTrue($storeResponseTransfer->getIsSuccessful());
        $this->assertTrue($this->tester->localeStoreExists($storeTransfer->getIdStoreOrFail(), $idLocaleDe));
        $this->assertTrue($this->tester->localeStoreExists($storeTransfer->getIdStoreOrFail(), $idLocaleUs));
        $this->assertFalse($this->tester->localeStoreExists($storeTransfer->getIdStoreOrFail(), $idLocaleFr));
    }

    /**
     * @return void
     */
    public function testExpandStoreTransfersWithLocalesSuccessful(): void
    {
        if ($this->tester->isDynamicStoreEnabled() === false) {
            $this->markTestSkipped('Test is valid for Dynamic Store on-mode only.');
        }

        // Arrange
        $storeTransferEu = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);

        $idLocaleDe = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_DE])->getIdLocaleOrFail();

        $this->tester->haveLocaleStore($storeTransferEu->getIdStoreOrFail(), $idLocaleDe);

        // Act
        $storeTransfers = $this->localeFacade->expandStoreTransfersWithLocales([
            $storeTransferEu->getIdStoreOrFail() => $storeTransferEu,
        ]);

        // Assert
        $this->assertTrue(in_array(static::LOCALE_EN, array_values($storeTransfers[$storeTransferEu->getIdStoreOrFail()]->getAvailableLocaleIsoCodes())));
        $this->assertTrue(in_array(static::LOCALE_DE, array_values($storeTransfers[$storeTransferEu->getIdStoreOrFail()]->getAvailableLocaleIsoCodes())));
    }

    /**
     * @return void
     */
    public function testExpandStoreTransfersWithLocalesWithoutLocaleStoreRelations(): void
    {
        if ($this->tester->isDynamicStoreEnabled() === false) {
            $this->markTestSkipped('Test is valid for Dynamic Store on-mode only.');
        }

        // Arrange
        $storeTransferEu = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);

        $this->tester->deleteLocaleStore($storeTransferEu->getIdStoreOrFail());

        // Act
        $storeTransfers = $this->localeFacade->expandStoreTransfersWithLocales([
            $storeTransferEu->getIdStoreOrFail() => $storeTransferEu,
        ]);

        // Assert
        $this->assertSame(
            [],
            array_values($storeTransfers[$storeTransferEu->getIdStoreOrFail()]->getAvailableLocaleIsoCodes()),
        );
    }

    /**
     * @return void
     */
    public function testValidateStoreLocaleSuccessful(): void
    {
        // Arrange
        $storeTransfer = (new StoreTransfer())
            ->setDefaultLocaleIsoCode(static::LOCALE_DE)
            ->setAvailableLocaleIsoCodes([static::LOCALE_EN, static::LOCALE_DE]);

        // Act
        $response = $this->localeFacade->validateStoreLocale($storeTransfer);

        // Assert
        $this->assertTrue($response->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testValidateStoreLocaleWithEmptyDefaultLocale(): void
    {
        // Arrange
        $storeTransfer = (new StoreTransfer())
            ->setAvailableLocaleIsoCodes([static::LOCALE_EN, static::LOCALE_DE]);

        // Act
        $response = $this->localeFacade->validateStoreLocale($storeTransfer);

        // Assert
        $this->assertFalse($response->getIsSuccessful());
        $this->assertSame(
            static::MESSAGE_DEFAULT_LOCALE_MUST_NOT_BE_EMPTY,
            $response->getMessages()->getArrayCopy()[0]->getValue(),
        );
    }

    /**
     * @return void
     */
    public function testValidateStoreLocaleWithInvalidDefaultLocale(): void
    {
        // Arrange
        $storeTransfer = (new StoreTransfer())
            ->setDefaultLocaleIsoCode(static::LOCALE_DE)
            ->setAvailableLocaleIsoCodes([static::LOCALE_EN]);

        // Act
        $response = $this->localeFacade->validateStoreLocale($storeTransfer);

        // Assert
        $this->assertFalse($response->getIsSuccessful());
        $this->assertSame(
            static::MESSAGE_DEFAULT_LOCALE_MUST_BE_PRESENTED_IN_AVAILABLE_LOCALES,
            $response->getMessages()->getArrayCopy()[0]->getValue(),
        );
    }

    /**
     * @return void
     */
    public function testUpdateStoreDefaultLocaleSuccessful(): void
    {
        if ($this->tester->isDynamicStoreEnabled() === false) {
            $this->markTestSkipped('Test is valid for Dynamic Store on-mode only.');
        }

        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $idLocaleDe = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_DE])->getIdLocaleOrFail();

        $storeTransfer->setDefaultLocaleIsoCode(static::LOCALE_DE);

        // Act
        $response = $this->localeFacade->updateStoreDefaultLocale($storeTransfer);

        // Assert
        $this->assertTrue($response->getIsSuccessful());
        $this->assertSame(
            $idLocaleDe,
            $this->tester->getDefaultLocaleByIdStore($storeTransfer->getIdStoreOrFail()),
        );
    }

    /**
     * @return void
     */
    public function testGetSupportedLocaleCodesReturnValueFromConfig(): void
    {
        // Act
        $backofficeUILocales = $this->localeFacade->getSupportedLocaleCodes();

        // Assert
        $this->assertSame(static::BACKOFFICE_UI_LOCALES, $backofficeUILocales);
    }

    /**
     * @return void
     */
    public function testGetLocaleCollectionGetsLocalesFromStoreInstance(): void
    {
        // Arrange
        $availableLocales = $this->localeFacade->getAvailableLocales();

        // Act
        $localeTransfers = $this->localeFacade->getLocaleCollection();

        // Assert
        $this->assertSame([], array_diff($availableLocales, array_keys($localeTransfers)));
        $this->assertSame([], array_diff(array_keys($localeTransfers), $availableLocales));

        foreach ($localeTransfers as $localeName => $localeTransfer) {
            $this->assertSame($localeName, $localeTransfer->getLocaleNameOrFail());
            $this->assertContains($localeTransfer->getLocaleNameOrFail(), $availableLocales);
        }
    }
}
