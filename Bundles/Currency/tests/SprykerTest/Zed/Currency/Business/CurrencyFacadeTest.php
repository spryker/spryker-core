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
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Currency\Business\CurrencyFacade;
use Spryker\Zed\Currency\Business\Validator\QuoteValidator;

/**
 * Auto-generated group annotations
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
    public function testValidationEmptyCurrencyInQuote()
    {
        $quoteTransfer = new QuoteTransfer();

        $quoteValidationResponseTransfer = $this->createCurrencyFacade()->validateCurrencyInQuote($quoteTransfer);

        $errors = array_map(function ($messageTransfer) {
            return $messageTransfer->getValue();
        }, (array)$quoteValidationResponseTransfer->getErrors());

        $this->assertFalse($quoteValidationResponseTransfer->getIsSuccess());
        $this->assertContains(QuoteValidator::MESSAGE_CURRENCY_DATA_IS_MISSING, $errors);
    }

    /**
     * @return void
     */
    public function testValidationEmptyCurrencyCodeInQuote()
    {
        $currencyTransfer = new CurrencyTransfer();
        $quoteTransfer = (new QuoteTransfer())
            ->setCurrency($currencyTransfer);

        $quoteValidationResponseTransfer = $this->createCurrencyFacade()->validateCurrencyInQuote($quoteTransfer);

        $errors = array_map(function ($messageTransfer) {
            return $messageTransfer->getValue();
        }, (array)$quoteValidationResponseTransfer->getErrors());

        $this->assertFalse($quoteValidationResponseTransfer->getIsSuccess());
        $this->assertContains(QuoteValidator::MESSAGE_CURRENCY_DATA_IS_MISSING, $errors);
    }

    /**
     * @return void
     */
    public function testValidationWrongCurrencyCodeInQuote()
    {
        $currencyTransfer = (new CurrencyTransfer())
            ->setCode('WRONGCODE');
        $storeTransfer = (new StoreTransfer())
            ->setName('DE');
        $quoteTransfer = (new QuoteTransfer())
            ->setCurrency($currencyTransfer)
            ->setStore($storeTransfer);

        $quoteValidationResponseTransfer = $this->createCurrencyFacade()->validateCurrencyInQuote($quoteTransfer);

        $errors = array_map(function ($messageTransfer) {
            return $messageTransfer->getValue();
        }, (array)$quoteValidationResponseTransfer->getErrors());

        $this->assertFalse($quoteValidationResponseTransfer->getIsSuccess());
        $this->assertContains(QuoteValidator::MESSAGE_CURRENCY_DATA_IS_INCORRECT, $errors);
    }

    /**
     * @return \Spryker\Zed\Currency\Business\CurrencyFacadeInterface
     */
    protected function createCurrencyFacade()
    {
        return new CurrencyFacade();
    }
}
