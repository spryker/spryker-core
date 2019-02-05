<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Price\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteTransfer;

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
    protected const MESSAGE_PRICE_MODE_DATA_IS_MISSING = 'quote.validation.error.price_mode_is_missing';
    protected const MESSAGE_PRICE_MODE_DATA_IS_INCORRECT = 'quote.validation.error.price_mode_is_incorrect';
    protected const WRONG_PRICE_MODE = 'WRONGPRICEMODE';

    /**
     * @var \SprykerTest\Zed\Price\PriceBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testValidatePriceModeInQuoteWithEmptyPriceMode()
    {
        $quoteTransfer = new QuoteTransfer();

        //Act
        $this->validatePriceModeInQuote($quoteTransfer, static::MESSAGE_PRICE_MODE_DATA_IS_MISSING);
    }

    /**
     * @return void
     */
    public function testValidatePriceModeInQuoteWithWrongPriceMode()
    {
        $quoteTransfer = (new QuoteTransfer())
            ->setPriceMode(static::WRONG_PRICE_MODE);

        //Act
        $this->validatePriceModeInQuote($quoteTransfer, static::MESSAGE_PRICE_MODE_DATA_IS_INCORRECT);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $message
     *
     * @return void
     */
    protected function validatePriceModeInQuote(QuoteTransfer $quoteTransfer, string $message): void
    {
        //Act
        /** @var \Spryker\Zed\Price\Business\PriceFacade $priceFacade */
        $priceFacade = $this->tester->getFacade();
        $quoteValidationResponseTransfer = $priceFacade->validatePriceModeInQuote($quoteTransfer);

        $errors = array_map(function ($messageTransfer) {
            return $messageTransfer->getValue();
        }, (array)$quoteValidationResponseTransfer->getErrors());

        $this->assertFalse($quoteValidationResponseTransfer->getIsSuccess());
        $this->assertContains($message, $errors);
    }
}
