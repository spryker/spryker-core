<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfigurationCart\Business\ProductConfigurationCartFacade;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductConfigurationCart
 * @group Business
 * @group ProductConfigurationCartFacade
 * @group ValidateQuoteRequestProductConfigurationTest
 * Add your own group annotations below this line
 */
class ValidateQuoteRequestProductConfigurationTest extends Unit
{
    /**
     * @uses \Spryker\Zed\ProductConfigurationCart\Business\Validator\QuoteRequestProductConfigurationValidator::GLOSSARY_KEY_PRODUCT_CONFIGURATION_IN_QUOTE_REQUEST_IS_INCOMPLETE
     * @var string
     */
    protected const GLOSSARY_KEY_PRODUCT_CONFIGURATION_IN_QUOTE_REQUEST_IS_INCOMPLETE = 'product_configuration.quote_request.validation.error.incomplete';

    /**
     * @var \SprykerTest\Zed\ProductConfigurationCart\ProductConfigurationCartBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testValidateQuoteRequestProductConfigurationWillReturnSuccessInCaseOfAllProductsCompleted(): void
    {
        //Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInDraftStatusWithCompleteConfiguredProduct();

        //Act
        $quoteRequestResponseTransfer = $this->tester->getFacade()
            ->validateQuoteRequestProductConfiguration($quoteRequestTransfer);

        //Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertEmpty($quoteRequestResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testValidateQuoteRequestProductConfigurationWillReturnFailInCaseOfAnyProductIsInCompleted(): void
    {
        //Arrange
        $quoteRequestTransfer = $this->tester->haveQuoteRequestInDraftStatusWithIncompleteConfiguredProduct();

        //Act
        $quoteRequestResponseTransfer = $this->tester->getFacade()
            ->validateQuoteRequestProductConfiguration($quoteRequestTransfer);

        //Assert
        $this->assertFalse($quoteRequestResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_PRODUCT_CONFIGURATION_IN_QUOTE_REQUEST_IS_INCOMPLETE,
            $quoteRequestResponseTransfer->getMessages()[0]->getValue(),
        );
    }
}
