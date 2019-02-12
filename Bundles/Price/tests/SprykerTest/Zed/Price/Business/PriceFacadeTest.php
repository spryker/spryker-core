<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Price\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Price
 * @group Business
 * @group Facade
 * @group PriceFacadeTest
 * Add your own group annotations below this line
 */
class PriceFacadeTest extends Unit
{
    protected const ERROR_MESSAGE_PRICE_MODE_DATA_IS_MISSING = 'quote.validation.error.price_mode_is_missing';
    protected const ERROR_MESSAGE_PRICE_MODE_DATA_IS_INCORRECT = 'quote.validation.error.price_mode_is_incorrect';
    protected const WRONG_PRICE_MODE = 'WRONGPRICEMODE';
    protected const GROSS_MODE = 'GROSS_MODE';

    /**
     * @var \SprykerTest\Zed\Price\PriceBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testValidateEmptyPriceModeInQuote(): void
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteValidationResponseTransfer = $this->getQuoteValidationResponseTransfer($quoteTransfer);

        //Act
        $this->validatePriceModeInQuote($quoteValidationResponseTransfer, static::ERROR_MESSAGE_PRICE_MODE_DATA_IS_MISSING);
    }

    /**
     * @return void
     */
    public function testValidateWrongPriceModeInQuote()
    {
        $quoteTransfer = (new QuoteTransfer())
            ->setPriceMode(static::WRONG_PRICE_MODE);
        $quoteValidationResponseTransfer = $this->getQuoteValidationResponseTransfer($quoteTransfer);

        //Act
        $this->validatePriceModeInQuote($quoteValidationResponseTransfer, static::ERROR_MESSAGE_PRICE_MODE_DATA_IS_INCORRECT);
    }

    /**
     * @return void
     */
    public function testValidateCorrectPriceModeInQuote()
    {
        $quoteTransfer = (new QuoteTransfer())
            ->setPriceMode(static::GROSS_MODE);
        $quoteValidationResponseTransfer = $this->getQuoteValidationResponseTransfer($quoteTransfer);

        //Act
        $this->assertTrue($quoteValidationResponseTransfer->getIsSuccess());
        $this->assertEmpty($quoteValidationResponseTransfer->getErrors());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteValidationResponseTransfer $quoteValidationResponseTransfer
     * @param string $errorMessage
     *
     * @return void
     */
    protected function validatePriceModeInQuote(QuoteValidationResponseTransfer $quoteValidationResponseTransfer, string $errorMessage): void
    {
        $errors = array_map(function ($errorMessageTransfer) {
            return $errorMessageTransfer->getValue();
        }, (array)$quoteValidationResponseTransfer->getErrors());

        $this->assertFalse($quoteValidationResponseTransfer->getIsSuccess());
        $this->assertContains($errorMessage, $errors);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    protected function getQuoteValidationResponseTransfer(QuoteTransfer $quoteTransfer): QuoteValidationResponseTransfer
    {
        /** @var \Spryker\Zed\Price\Business\PriceFacade $priceFacade */
        $priceFacade = $this->tester->getFacade();

        return $priceFacade->validatePriceModeInQuote($quoteTransfer);
    }
}
