<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfigurationCart\ProductConfigurationCartClient;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductConfigurationCart
 * @group ProductConfigurationCartClient
 * @group IsQuoteProductConfigurationValidTest
 * Add your own group annotations below this line
 */
class IsQuoteProductConfigurationValidTest extends Unit
{
    /**
     * @var \SprykerTest\Client\ProductConfigurationCart\ProductConfigurationCartClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testIsQuoteProductConfigurationValidWithSuccessFlow(): void
    {
        // Arrange
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceTransfer())->setIsComplete(true);

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::PRODUCT_CONFIGURATION_INSTANCE => $productConfigurationInstanceTransfer,
        ]))->build();

        // Act
        $isQuoteProductConfigurationValid = $this->tester->getClient()->isQuoteProductConfigurationValid(
            (new QuoteTransfer())->addItem($itemTransfer)
        );

        // Assert
        $this->assertTrue(
            $isQuoteProductConfigurationValid,
            'Expects that product configuration will be valid.'
        );
    }

    /**
     * @return void
     */
    public function testIsQuoteProductConfigurationValidFalseWithNotCompletedProductConfiguration(): void
    {
        // Arrange
        $productConfigurationInstanceTransfer = (new ProductConfigurationInstanceTransfer())
            ->setIsComplete(false);

        $itemTransfer = (new ItemBuilder([
            ItemTransfer::PRODUCT_CONFIGURATION_INSTANCE => $productConfigurationInstanceTransfer,
        ]))->build();

        // Act
        $isQuoteProductConfigurationValid = $this->tester->getClient()->isQuoteProductConfigurationValid(
            (new QuoteTransfer())->addItem($itemTransfer)
        );

        // Assert
        $this->assertFalse(
            $isQuoteProductConfigurationValid,
            'Expects that product configuration will be not valid with not completed product configuration.'
        );
    }

    /**
     * @return void
     */
    public function testIsQuoteProductConfigurationValidEmptyQuoteDoNothing(): void
    {
        // Act
        $isQuoteProductConfigurationValid = $this->tester->getClient()->isQuoteProductConfigurationValid(
            (new QuoteTransfer())
        );

        // Assert
        $this->assertTrue(
            $isQuoteProductConfigurationValid,
            'Expects that product configuration will be valid with empty quote.'
        );
    }

    /**
     * @return void
     */
    public function testIsQuoteProductConfigurationValidItemsWithoutConfiguration(): void
    {
        $itemTransfer = (new ItemBuilder())->build();

        // Act
        $isQuoteProductConfigurationValid = $this->tester->getClient()->isQuoteProductConfigurationValid(
            (new QuoteTransfer())->addItem($itemTransfer)
        );

        // Assert
        $this->assertTrue(
            $isQuoteProductConfigurationValid,
            'Expects that product configuration will be valid when item does not have product configuration.'
        );
    }
}
