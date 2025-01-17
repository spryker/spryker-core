<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendment\Communication\Plugin\CartReorder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\SalesOrderAmendment\Communication\Plugin\CartReorder\AmendmentOrderReferenceCartPreReorderPlugin;
use Spryker\Zed\SalesOrderAmendment\Communication\Plugin\CartReorder\OrderAmendmentQuoteProcessFlowExpanderCartPreReorderPlugin;
use SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendment
 * @group Communication
 * @group Plugin
 * @group CartReorder
 * @group OrderAmendmentQuoteProcessFlowCartPreReorderPluginTest
 * Add your own group annotations below this line
 */
class OrderAmendmentQuoteProcessFlowCartPreReorderPluginTest extends Unit
{
    /**
     * @uses \Spryker\Zed\SalesOrderAmendment\SalesOrderAmendmentConfig::ORDER_AMENDMENT_QUOTE_PROCESS_FLOW_NAME
     *
     * @var string
     */
    protected const ORDER_AMENDMENT_QUOTE_PROCESS_FLOW_NAME = 'order-amendment';

    /**
     * @var \SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentCommunicationTester
     */
    protected SalesOrderAmendmentCommunicationTester $tester;

    /**
     * @return void
     */
    public function testShouldSetOrderAmendmentQuoteProcessFlowWhenIsAmendmentFlagIsSetToTrue(): void
    {
        // Arrange
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())->setIsAmendment(true);
        $cartReorderTransfer = (new CartReorderTransfer())->setQuote(new QuoteTransfer());

        // Arrange
        $cartReorderTransfer = (new OrderAmendmentQuoteProcessFlowExpanderCartPreReorderPlugin())
            ->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);

        // Assert
        $this->assertNotNull($cartReorderTransfer->getQuoteOrFail()->getQuoteProcessFlow());
        $this->assertSame(
            static::ORDER_AMENDMENT_QUOTE_PROCESS_FLOW_NAME,
            $cartReorderTransfer->getQuoteOrFail()->getQuoteProcessFlowOrFail()->getName(),
        );
    }

    /**
     * @return void
     */
    public function testShouldDoNothingWhenIsAmendmentFlagIsSetToFalse(): void
    {
        // Arrange
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())->setIsAmendment(false);
        $cartReorderTransfer = (new CartReorderTransfer())->setQuote(new QuoteTransfer());

        // Arrange
        $cartReorderTransfer = (new OrderAmendmentQuoteProcessFlowExpanderCartPreReorderPlugin())
            ->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);

        // Assert
        $this->assertNull($cartReorderTransfer->getQuoteOrFail()->getQuoteProcessFlow());
    }

    /**
     * @return void
     */
    public function testShouldDoNothingWhenIsAmendmentFlagIsNotSet(): void
    {
        // Arrange
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())->setIsAmendment(null);
        $cartReorderTransfer = (new CartReorderTransfer())->setQuote(new QuoteTransfer());

        // Arrange
        $cartReorderTransfer = (new OrderAmendmentQuoteProcessFlowExpanderCartPreReorderPlugin())
            ->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);

        // Assert
        $this->assertNull($cartReorderTransfer->getQuoteOrFail()->getQuoteProcessFlow());
    }

    /**
     * @return void
     */
    public function testShouldThrowsNullValueExceptionWhenQuoteIsNotProvided(): void
    {
        // Arrange
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())->setIsAmendment(true);
        $cartReorderTransfer = new CartReorderTransfer();

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "quote" of transfer `Generated\Shared\Transfer\CartReorderTransfer` is null.');

        // Act
        (new AmendmentOrderReferenceCartPreReorderPlugin())->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);
    }
}
