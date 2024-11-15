<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendment\Communication\Plugin\Quote;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\SalesOrderAmendment\Communication\Plugin\Quote\ResetQuoteNameQuoteBeforeSavePlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendment
 * @group Communication
 * @group Plugin
 * @group Quote
 * @group ResetQuoteNameQuoteBeforeSavePluginTest
 * Add your own group annotations below this line
 */
class ResetQuoteNameQuoteBeforeSavePluginTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_ORDER_REFERENCE = 'DE--123';

    /**
     * @var string
     */
    protected const FAKE_QUOTE_NAME = 'FAKE_QUOTE_NAME';

    /**
     * @return void
     */
    public function testResetQuoteNameWhenQuoteDoNotHaveItemsAndOrderAmendmentReferenceIsSet(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::ITEMS => [],
            QuoteTransfer::AMENDMENT_ORDER_REFERENCE => static::FAKE_ORDER_REFERENCE,
            QuoteTransfer::NAME => static::FAKE_QUOTE_NAME,
        ]))->build();

        // Act
        $quoteTransfer = (new ResetQuoteNameQuoteBeforeSavePlugin())->execute($quoteTransfer);

        // Assert
        $this->assertNull($quoteTransfer->getName());
    }

    /**
     * @return void
     */
    public function testDoesNothingWhenQuoteHaveItems(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::ITEMS => [
                new ItemTransfer(),
                new ItemTransfer(),
            ],
            QuoteTransfer::AMENDMENT_ORDER_REFERENCE => null,
            QuoteTransfer::NAME => static::FAKE_QUOTE_NAME,
        ]))->build();

        // Act
        $quoteTransfer = (new ResetQuoteNameQuoteBeforeSavePlugin())->execute($quoteTransfer);

        // Assert
        $this->assertSame(static::FAKE_QUOTE_NAME, $quoteTransfer->getName());
    }

    /**
     * @return void
     */
    public function testDoesNothingWhenQuoteDoNotHaveOrderAmendmentReference(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder([
            QuoteTransfer::AMENDMENT_ORDER_REFERENCE => static::FAKE_ORDER_REFERENCE,
            QuoteTransfer::NAME => static::FAKE_QUOTE_NAME,
        ]))->withItem()->build();

        // Act
        $quoteTransfer = (new ResetQuoteNameQuoteBeforeSavePlugin())->execute($quoteTransfer);

        // Assert
        $this->assertSame(static::FAKE_QUOTE_NAME, $quoteTransfer->getName());
    }
}
