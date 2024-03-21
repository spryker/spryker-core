<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Currency\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CurrencyBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Currency\Business\CurrencyFacade;
use Spryker\Zed\Currency\Business\CurrencyFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Currency
 * @group Business
 * @group Facade
 * @group CurrencyFacadeTest
 * Add your own group annotations below this line
 */
class CurrencyFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_CURRENCY_DATA_IS_MISSING = 'quote.validation.error.currency_is_missing';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_CURRENCY_DATA_IS_INCORRECT = 'quote.validation.error.currency_is_incorrect';

    /**
     * @var string
     */
    protected const WRONG_ISO_CODE = 'WRONGCODE';

    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var int
     */
    protected const STORE_ID_DE = 1;

    /**
     * @var string
     */
    protected const STORE_NAME_US = 'US';

    /**
     * @var string
     */
    protected const EUR_ISO_CODE = 'EUR';

    /**
     * @var string
     */
    protected const USD_ISO_CODE = 'USD';

    /**
     * @var string
     */
    protected const GBP_ISO_CODE = 'GBP';

    /**
     * @var \SprykerTest\Zed\Currency\CurrencyBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetByIdCurrencyShouldReturnCurrencyTransfer(): void
    {
        $idCurrency = $this->tester->haveCurrency();
        $currencyTransfer = $this->tester->getFacade()->getByIdCurrency($idCurrency);

        $this->assertNotNull($currencyTransfer);
    }

    /**
     * @return void
     */
    public function testCreateCurrencyShouldPersistGivenData(): void
    {
        $currencyTransfer = (new CurrencyBuilder())->build();

        $idCurrency = $this->tester->getFacade()->createCurrency($currencyTransfer);

        $this->assertNotNull($idCurrency);
    }

    /**
     * @return void
     */
    public function testGetByIdCurrencyShouldReturnCurrencyFromPersistence(): void
    {
        $currencyTransfer = $this->tester->getFacade()->getByIdCurrency(1);

        $this->assertInstanceOf(CurrencyTransfer::class, $currencyTransfer);
    }

    /**
     * @return void
     */
    public function testValidateCurrencyInQuoteWithEmptyCurrency(): void
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteValidationResponseTransfer = $this->getQuoteValidationResponseTransfer($quoteTransfer);

        $errors = array_map(function ($quoteErrorTransfer) {
            return $quoteErrorTransfer->getMessage();
        }, (array)$quoteValidationResponseTransfer->getErrors());

        //Act
        $this->assertFalse($quoteValidationResponseTransfer->getIsSuccessful());
        $this->assertContains(static::ERROR_MESSAGE_CURRENCY_DATA_IS_MISSING, $errors);
    }

    /**
     * @return void
     */
    public function testValidateCurrencyInQuoteWithEmptyCurrencyIsoCode(): void
    {
        $currencyTransfer = new CurrencyTransfer();
        $quoteTransfer = (new QuoteTransfer())
            ->setCurrency($currencyTransfer);
        $quoteValidationResponseTransfer = $this->getQuoteValidationResponseTransfer($quoteTransfer);

        $errors = array_map(function ($quoteErrorTransfer) {
            return $quoteErrorTransfer->getMessage();
        }, (array)$quoteValidationResponseTransfer->getErrors());

        //Act
        $this->assertFalse($quoteValidationResponseTransfer->getIsSuccessful());
        $this->assertContains(static::ERROR_MESSAGE_CURRENCY_DATA_IS_MISSING, $errors);
    }

    /**
     * @return void
     */
    public function testValidateCurrencyInQuoteWithWrongCurrencyIsoCode(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);

        $currencyTransfer = (new CurrencyTransfer())
            ->setCode(static::WRONG_ISO_CODE);
        $quoteTransfer = (new QuoteTransfer())
            ->setCurrency($currencyTransfer)
            ->setStore($storeTransfer);

        // Act
        $quoteValidationResponseTransfer = $this->getQuoteValidationResponseTransfer($quoteTransfer);

        // Assert
        $errors = array_map(function ($quoteErrorTransfer) {
            return $quoteErrorTransfer->getMessage();
        }, (array)$quoteValidationResponseTransfer->getErrors());

        $this->assertFalse($quoteValidationResponseTransfer->getIsSuccessful());
        $this->assertContains(static::ERROR_MESSAGE_CURRENCY_DATA_IS_INCORRECT, $errors);
    }

    /**
     * @return void
     */
    public function testValidateCurrencyInQuoteWithCorrectIsoCode(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $storeTransfer->addAvailableCurrencyIsoCode(static::EUR_ISO_CODE);

        $currencyTransfer = (new CurrencyTransfer())
            ->setCode(static::EUR_ISO_CODE);
        $storeTransfer = (new StoreTransfer())
            ->setName(static::STORE_NAME_DE);
        $quoteTransfer = (new QuoteTransfer())
            ->setCurrency($currencyTransfer)
            ->setStore($storeTransfer);

        // Act
        $quoteValidationResponseTransfer = $this->getQuoteValidationResponseTransfer($quoteTransfer);

        // Assert
        $this->assertTrue($quoteValidationResponseTransfer->getIsSuccessful());
        $this->assertEmpty($quoteValidationResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testUpdateStoreCountriesWithAddingNewAndRemovingOldRelations(): void
    {
        if ($this->tester->isDynamicStoreEnabled() === false) {
            $this->markTestSkipped('Test is valid for Dynamic Store on-mode only.');
        }

        // Arrange
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => static::STORE_NAME_DE,
        ]);
        $this->tester->deleteCurrencyStore($storeTransfer->getIdStoreOrFail());

        $idCurrencyEur = $this->tester->haveCurrency([CurrencyTransfer::CODE => static::EUR_ISO_CODE]);
        $idCurrencyGbp = $this->tester->haveCurrency([CurrencyTransfer::CODE => static::GBP_ISO_CODE]);
        $idCurrencyUsd = $this->tester->haveCurrency([CurrencyTransfer::CODE => static::USD_ISO_CODE]);

        $this->tester->haveCurrencyStore($storeTransfer->getIdStoreOrFail(), $idCurrencyEur);
        $this->tester->haveCurrencyStore($storeTransfer->getIdStoreOrFail(), $idCurrencyGbp);

        $storeTransfer->setDefaultCurrencyIsoCode(static::EUR_ISO_CODE);
        $storeTransfer->setAvailableCurrencyIsoCodes([static::EUR_ISO_CODE, static::USD_ISO_CODE]);

        // Act
        $storeResponseTransfer = $this->createCurrencyFacade()->updateStoreCurrencies($storeTransfer);

        // Assert
        $this->assertTrue($storeResponseTransfer->getIsSuccessful());
        $this->assertTrue($this->tester->currencyStoreExists($storeTransfer->getIdStoreOrFail(), $idCurrencyEur));
        $this->assertTrue($this->tester->currencyStoreExists($storeTransfer->getIdStoreOrFail(), $idCurrencyUsd));
        $this->assertFalse($this->tester->currencyStoreExists($storeTransfer->getIdStoreOrFail(), $idCurrencyGbp));
    }

    /**
     * @return void
     */
    public function testExpandStoreTransfersWithCurrenciesSuccessful(): void
    {
        if ($this->tester->isDynamicStoreEnabled() === false) {
            $this->markTestSkipped('Test is valid for Dynamic Store on-mode only.');
        }

        // Arrange
        $storeTransferEu = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);

        $this->tester->deleteCurrencyStore($storeTransferEu->getIdStore());

        $idCurrencyEur = $this->tester->haveCurrency([CurrencyTransfer::CODE => static::EUR_ISO_CODE]);
        $idCurrencyUsd = $this->tester->haveCurrency([CurrencyTransfer::CODE => static::USD_ISO_CODE]);

        $this->tester->haveCurrencyStore($storeTransferEu->getIdStoreOrFail(), $idCurrencyEur);

        // Act
        $storeTransfers = $this->createCurrencyFacade()->expandStoreTransfersWithCurrencies([
            $storeTransferEu->getIdStoreOrFail() => $storeTransferEu,
        ]);

        // Assert
        $this->assertSame(
            [static::EUR_ISO_CODE],
            array_values($storeTransfers[$storeTransferEu->getIdStoreOrFail()]->getAvailableCurrencyIsoCodes()),
        );
    }

    /**
     * @return void
     */
    public function testExpandStoreTransfersWithCurrenciesSuccessfulWithoutCurrencyStoreRelations(): void
    {
        if ($this->tester->isDynamicStoreEnabled() === false) {
            $this->markTestSkipped('Test is valid for Dynamic Store on-mode only.');
        }

        // Arrange
        $storeTransferEu = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);

        $this->tester->deleteCurrencyStore($storeTransferEu->getIdStoreOrFail());

        // Act
        $storeTransfers = $this->createCurrencyFacade()->expandStoreTransfersWithCurrencies([
            $storeTransferEu->getIdStoreOrFail() => $storeTransferEu,
        ]);

        // Assert
        $this->assertSame(
            [],
            array_values($storeTransfers[$storeTransferEu->getIdStoreOrFail()]->getAvailableCurrencyIsoCodes()),
        );
    }

    /**
     * @return \Spryker\Zed\Currency\Business\CurrencyFacadeInterface
     */
    protected function createCurrencyFacade(): CurrencyFacadeInterface
    {
        return new CurrencyFacade();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    protected function getQuoteValidationResponseTransfer(QuoteTransfer $quoteTransfer): QuoteValidationResponseTransfer
    {
        return $this->tester->getFacade()->validateCurrencyInQuote($quoteTransfer);
    }
}
