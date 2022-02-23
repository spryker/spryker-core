<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteApproval\Communication\Plugin\Quote;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteApprovalTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use ReflectionClass;
use Spryker\Zed\QuoteApproval\Communication\Plugin\Quote\QuoteApprovalExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group QuoteApproval
 * @group Communication
 * @group Plugin
 * @group Quote
 * @group QuoteApprovalExpanderPluginTest
 * Add your own group annotations below this line
 */
class QuoteApprovalExpanderPluginTest extends Unit
{
    /**
     * @var int
     */
    protected const FAKE_ID_QUOTE = 12345;

    /**
     * @return void
     */
    public function testExpandQuoteWithPreloadedQuoteApprovals(): void
    {
        // Arrange
        $quoteApprovalExpanderPlugin = $this->createQuoteApprovalExpanderPlugin();
        $this->updateQuoteApprovalsByIdQuoteProperty(
            $quoteApprovalExpanderPlugin,
            [
            static::FAKE_ID_QUOTE => [
                (new QuoteApprovalTransfer())->setFkQuote(static::FAKE_ID_QUOTE),
                (new QuoteApprovalTransfer())->setFkQuote(static::FAKE_ID_QUOTE),
            ]],
        );

        // Act
        $quoteTransfer = $quoteApprovalExpanderPlugin->expand((new QuoteTransfer())->setIdQuote(static::FAKE_ID_QUOTE));

        // Assert
        $this->assertCount(2, $quoteTransfer->getQuoteApprovals());
    }

    /**
     * @param \Spryker\Zed\QuoteApproval\Communication\Plugin\Quote\QuoteApprovalExpanderPlugin $quoteApprovalExpanderPlugin
     * @param array<int, array<\Generated\Shared\Transfer\QuoteApprovalTransfer>> $indexedQuoteApprovals
     *
     * @return void
     */
    protected function updateQuoteApprovalsByIdQuoteProperty(
        QuoteApprovalExpanderPlugin $quoteApprovalExpanderPlugin,
        array $indexedQuoteApprovals
    ): void {
        $reflectedClass = new ReflectionClass(QuoteApprovalExpanderPlugin::class);
        $property = $reflectedClass->getProperty('quoteApprovalsByIdQuote');
        $property->setAccessible(true);
        $property->setValue($quoteApprovalExpanderPlugin, $indexedQuoteApprovals);
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Communication\Plugin\Quote\QuoteApprovalExpanderPlugin
     */
    protected function createQuoteApprovalExpanderPlugin(): QuoteApprovalExpanderPlugin
    {
        return new QuoteApprovalExpanderPlugin();
    }
}
