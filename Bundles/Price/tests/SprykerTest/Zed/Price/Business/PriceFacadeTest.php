<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Price\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Price\Business\PriceFacade;
use Spryker\Zed\Price\Business\Validator\QuoteValidator;

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
    /**
     * @return void
     */
    public function testValidationEmptyPriceModeInQuote()
    {
        $quoteTransfer = new QuoteTransfer();

        //Act
        $quoteValidationResponseTransfer = $this->createPriceFacade()->validatePriceModeInQuote($quoteTransfer);

        $errors = array_map(function ($messageTransfer) {
            return $messageTransfer->getValue();
        }, (array)$quoteValidationResponseTransfer->getErrors());

        $this->assertFalse($quoteValidationResponseTransfer->getIsSuccess());
        $this->assertContains(QuoteValidator::MESSAGE_PRICE_MODE_DATA_IS_MISSING, $errors);
    }

    /**
     * @return void
     */
    public function testValidationWrongPriceModeInQuote()
    {
        $quoteTransfer = (new QuoteTransfer())
            ->setPriceMode('WRONGPRICEMODE');

        //Act
        $quoteValidationResponseTransfer = $this->createPriceFacade()->validatePriceModeInQuote($quoteTransfer);

        $errors = array_map(function ($messageTransfer) {
            return $messageTransfer->getValue();
        }, (array)$quoteValidationResponseTransfer->getErrors());

        $this->assertFalse($quoteValidationResponseTransfer->getIsSuccess());
        $this->assertContains(QuoteValidator::MESSAGE_PRICE_MODE_DATA_IS_INCORRECT, $errors);
    }

    /**
     * @return \Spryker\Zed\Price\Business\PriceFacadeInterface
     */
    protected function createPriceFacade()
    {
        return new PriceFacade();
    }
}
