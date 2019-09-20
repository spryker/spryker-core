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
    protected const ERROR_MESSAGE_CURRENCY_DATA_IS_MISSING = 'quote.validation.error.currency_is_missing';
    protected const ERROR_MESSAGE_CURRENCY_DATA_IS_INCORRECT = 'quote.validation.error.currency_is_incorrect';
    protected const WRONG_ISO_CODE = 'WRONGCODE';
    protected const STORE_NAME = 'DE';
    protected const EUR_ISO_CODE = 'EUR';

    /**
     * @var \SprykerTest\Zed\Currency\CurrencyBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetByIdCurrencyShouldReturnCurrencyTransfer()
    {
        $idCurrency = $this->tester->haveCurrency();
        $currencyTransfer = $this->createCurrencyFacade()->getByIdCurrency($idCurrency);

        $this->assertNotNull($currencyTransfer);
    }

    /**
     * @return void
     */
    public function testCreateCurrencyShouldPersistGivenData()
    {
        $currencyTransfer = (new CurrencyBuilder())->build();

        $idCurrency = $this->createCurrencyFacade()->createCurrency($currencyTransfer);

        $this->assertNotNull($idCurrency);
    }

    /**
     * @return void
     */
    public function testGetByIdCurrencyShouldReturnCurrencyFromPersistence()
    {
        $currencyTransfer = $this->createCurrencyFacade()->getByIdCurrency(1);

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
        $currencyTransfer = (new CurrencyTransfer())
            ->setCode(static::WRONG_ISO_CODE);
        $storeTransfer = (new StoreTransfer())
            ->setName(static::STORE_NAME);
        $quoteTransfer = (new QuoteTransfer())
            ->setCurrency($currencyTransfer)
            ->setStore($storeTransfer);
        $quoteValidationResponseTransfer = $this->getQuoteValidationResponseTransfer($quoteTransfer);

        $errors = array_map(function ($quoteErrorTransfer) {
            return $quoteErrorTransfer->getMessage();
        }, (array)$quoteValidationResponseTransfer->getErrors());

        //Act
        $this->assertFalse($quoteValidationResponseTransfer->getIsSuccessful());
        $this->assertContains(static::ERROR_MESSAGE_CURRENCY_DATA_IS_INCORRECT, $errors);
    }

    /**
     * @return void
     */
    public function testValidateCurrencyInQuoteWithCorrectIsoCode(): void
    {
        $currencyTransfer = (new CurrencyTransfer())
            ->setCode(static::EUR_ISO_CODE);
        $storeTransfer = (new StoreTransfer())
            ->setName(static::STORE_NAME);
        $quoteTransfer = (new QuoteTransfer())
            ->setCurrency($currencyTransfer)
            ->setStore($storeTransfer);
        $quoteValidationResponseTransfer = $this->getQuoteValidationResponseTransfer($quoteTransfer);

        //Act

        $this->assertTrue($quoteValidationResponseTransfer->getIsSuccessful());
        $this->assertEmpty($quoteValidationResponseTransfer->getErrors());
    }

    /**
     * @return \Spryker\Zed\Currency\Business\CurrencyFacadeInterface
     */
    protected function createCurrencyFacade()
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
        /** @var \Spryker\Zed\Currency\Business\CurrencyFacade $currencyFacade */
        $currencyFacade = $this->tester->getFacade();

        return $currencyFacade->validateCurrencyInQuote($quoteTransfer);
    }
}
