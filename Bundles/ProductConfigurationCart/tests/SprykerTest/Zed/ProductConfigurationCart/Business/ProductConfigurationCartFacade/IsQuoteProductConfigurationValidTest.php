<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfigurationCart\Business\ProductConfigurationCartFacade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductConfigurationCart
 * @group Business
 * @group ProductConfigurationCartFacade
 * @group IsQuoteProductConfigurationValidTest
 * Add your own group annotations below this line
 */
class IsQuoteProductConfigurationValidTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_GROUP_KEY = 'test_group_key';

    /**
     * @var \SprykerTest\Zed\ProductConfigurationCart\ProductConfigurationCartBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testWithValidProductConfiguration(): void
    {
        // Arrange
        $productConfigurationInstance = (new ProductConfigurationInstanceTransfer())->setIsComplete(true);

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::GROUP_KEY => static::TEST_GROUP_KEY,
            ItemTransfer::PRODUCT_CONFIGURATION_INSTANCE => $productConfigurationInstance,
        ]))->build();

        $quoteTransfer = (new QuoteTransfer())->addItem($itemTransfer);

        // Act
        $isQuoteProductConfigurationValid = $this->tester->getFacade()
            ->isQuoteProductConfigurationValid($quoteTransfer, new CheckoutResponseTransfer());

        // Assert
        $this->assertTrue(
            $isQuoteProductConfigurationValid,
            'Expects that quote transfer will be valid when product configuration is valid.',
        );
    }

    /**
     * @return void
     */
    public function testWithNotValidProductConfiguration(): void
    {
        // Arrange
        $productConfigurationInstance = (new ProductConfigurationInstanceTransfer())->setIsComplete(false);

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::GROUP_KEY => static::TEST_GROUP_KEY,
            ItemTransfer::PRODUCT_CONFIGURATION_INSTANCE => $productConfigurationInstance,
        ]))->build();

        $quoteTransfer = (new QuoteTransfer())->addItem($itemTransfer);

        // Act
        $isQuoteProductConfigurationValid = $this->tester->getFacade()
            ->isQuoteProductConfigurationValid($quoteTransfer, new CheckoutResponseTransfer());

        // Assert
        $this->assertFalse(
            $isQuoteProductConfigurationValid,
            'Expects that quote transfer will be not valid when product configuration not valid.',
        );
    }

    /**
     * @return void
     */
    public function testWithoutProductConfiguration(): void
    {
        // Arrange
        $itemTransfer = (new ItemBuilder([
            ItemTransfer::GROUP_KEY => static::TEST_GROUP_KEY,
        ]))->build();

        $quoteTransfer = (new QuoteTransfer())->addItem($itemTransfer);

        // Act
        $isQuoteProductConfigurationValid = $this->tester->getFacade()
            ->isQuoteProductConfigurationValid($quoteTransfer, new CheckoutResponseTransfer());

        // Assert
        $this->assertTrue(
            $isQuoteProductConfigurationValid,
            'Expects that quote transfer will be valid when product configuration was not set.',
        );
    }
}
