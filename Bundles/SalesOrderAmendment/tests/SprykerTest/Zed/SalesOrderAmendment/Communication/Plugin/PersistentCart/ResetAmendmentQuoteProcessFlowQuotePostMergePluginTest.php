<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendment\Communication\Plugin\PersistentCart;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\QuoteProcessFlowTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\SalesOrderAmendment\Communication\Plugin\PersistentCart\ResetAmendmentQuoteProcessFlowQuotePostMergePlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendment
 * @group Communication
 * @group Plugin
 * @group PersistentCart
 * @group ResetAmendmentQuoteProcessFlowQuotePostMergePluginTest
 * Add your own group annotations below this line
 */
class ResetAmendmentQuoteProcessFlowQuotePostMergePluginTest extends Unit
{
    /**
     * @uses \Spryker\Shared\SalesOrderAmendmentExtension\SalesOrderAmendmentExtensionContextsInterface::CONTEXT_ORDER_AMENDMENT
     *
     * @var string
     */
    protected const CONTEXT_ORDER_AMENDMENT = 'order-amendment';

    /**
     * @return void
     */
    public function testShouldResetQuoteProcessFlowForDifferentQuotes(): void
    {
        // Arrange
        $persistentQuoteTransfer = (new QuoteBuilder([
            QuoteTransfer::ID_QUOTE => 1,
            QuoteTransfer::QUOTE_PROCESS_FLOW => (new QuoteProcessFlowTransfer())->setName(static::CONTEXT_ORDER_AMENDMENT),
        ]))->build();

        $currentQuoteTransfer = (new QuoteBuilder([
            QuoteTransfer::ID_QUOTE => 2,
        ]))->build();

        // Act
        $persistentQuoteTransfer = (new ResetAmendmentQuoteProcessFlowQuotePostMergePlugin())->postMerge(
            $persistentQuoteTransfer,
            $currentQuoteTransfer,
        );

        // Assert
        $this->assertNull($persistentQuoteTransfer->getQuoteProcessFlow());
    }

    /**
     * @return void
     */
    public function testShouldNotResetQuoteProcessFlowForSameQuotes(): void
    {
        // Arrange
        $persistentQuoteTransfer = (new QuoteBuilder([
            QuoteTransfer::ID_QUOTE => 1,
            QuoteTransfer::QUOTE_PROCESS_FLOW => (new QuoteProcessFlowTransfer())->setName(static::CONTEXT_ORDER_AMENDMENT),
        ]))->build();

        $currentQuoteTransfer = (new QuoteBuilder([
            QuoteTransfer::ID_QUOTE => 1,
        ]))->build();

        // Act
        $persistentQuoteTransfer = (new ResetAmendmentQuoteProcessFlowQuotePostMergePlugin())->postMerge(
            $persistentQuoteTransfer,
            $currentQuoteTransfer,
        );

        // Assert
        $this->assertNotNull($persistentQuoteTransfer->getQuoteProcessFlow());
    }

    /**
     * @return void
     */
    public function testShouldThrowNullValueExceptionWhenPersistentQuoteIdIsNotDefined(): void
    {
        // Arrange
        $persistentQuoteTransfer = (new QuoteBuilder([
            QuoteTransfer::ID_QUOTE => null,
            QuoteTransfer::QUOTE_PROCESS_FLOW => (new QuoteProcessFlowTransfer())->setName(static::CONTEXT_ORDER_AMENDMENT),
        ]))->build();

        $currentQuoteTransfer = (new QuoteBuilder([
            QuoteTransfer::ID_QUOTE => 1,
        ]))->build();

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "idQuote" of transfer `Generated\Shared\Transfer\QuoteTransfer` is null.');

        // Act
        (new ResetAmendmentQuoteProcessFlowQuotePostMergePlugin())->postMerge(
            $persistentQuoteTransfer,
            $currentQuoteTransfer,
        );
    }

    /**
     * @return void
     */
    public function testShouldThrowNullValueExceptionWhenCurrentQuoteIdIsNotDefined(): void
    {
        // Arrange
        $persistentQuoteTransfer = (new QuoteBuilder([
            QuoteTransfer::ID_QUOTE => 1,
            QuoteTransfer::QUOTE_PROCESS_FLOW => (new QuoteProcessFlowTransfer())->setName(static::CONTEXT_ORDER_AMENDMENT),
        ]))->build();

        $currentQuoteTransfer = (new QuoteBuilder([
            QuoteTransfer::ID_QUOTE => null,
        ]))->build();

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "idQuote" of transfer `Generated\Shared\Transfer\QuoteTransfer` is null.');

        // Act
        (new ResetAmendmentQuoteProcessFlowQuotePostMergePlugin())->postMerge(
            $persistentQuoteTransfer,
            $currentQuoteTransfer,
        );
    }
}
