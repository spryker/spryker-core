<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendmentOms\Communication\Plugin\Oms;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentQuoteTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\SalesOrderAmendmentOms\Communication\Plugin\Oms\IsOrderAmendmentDraftSuccessfullyAppliedConditionPlugin;
use SprykerTest\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendmentOms
 * @group Communication
 * @group Plugin
 * @group Oms
 * @group IsOrderAmendmentDraftSuccessfullyAppliedConditionPluginTest
 * Add your own group annotations below this line
 */
class IsOrderAmendmentDraftSuccessfullyAppliedConditionPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const ORDER_REFERENCE = 'test-order-reference';

    /**
     * @var \SprykerTest\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsCommunicationTester
     */
    protected SalesOrderAmendmentOmsCommunicationTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureSalesOrderAmendmentQuoteTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testCheckReturnsTrueWhenSalesOrderAmendmentQuoteIsNotFound(): void
    {
        // Act
        $result = (new IsOrderAmendmentDraftSuccessfullyAppliedConditionPlugin())->check($this->createSalesOrderItemMock());

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCheckReturnsTrueWhenSalesOrderAmendmentQuoteHasNoErrors(): void
    {
        // Arrange
        $this->tester->haveSalesOrderAmendmentQuote([
            SalesOrderAmendmentQuoteTransfer::AMENDMENT_ORDER_REFERENCE => static::ORDER_REFERENCE,
            SalesOrderAmendmentQuoteTransfer::QUOTE => new QuoteTransfer(),
        ]);

        // Act
        $result = (new IsOrderAmendmentDraftSuccessfullyAppliedConditionPlugin())->check($this->createSalesOrderItemMock());

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCheckReturnsFalseWhenSalesOrderAmendmentQuoteHasErrors(): void
    {
        // Arrange
        $this->tester->haveSalesOrderAmendmentQuote([
            SalesOrderAmendmentQuoteTransfer::AMENDMENT_ORDER_REFERENCE => static::ORDER_REFERENCE,
            SalesOrderAmendmentQuoteTransfer::QUOTE => (new QuoteTransfer())->addError(new ErrorTransfer()),
        ]);

        // Act
        $result = (new IsOrderAmendmentDraftSuccessfullyAppliedConditionPlugin())->check($this->createSalesOrderItemMock());

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    protected function createSalesOrderItemMock(): SpySalesOrderItem
    {
        $orderEntityMock = $this->createMock(SpySalesOrder::class);
        $orderEntityMock->method('getOrderReference')->willReturn(static::ORDER_REFERENCE);

        $orderItemEntityMock = $this->createMock(SpySalesOrderItem::class);
        $orderItemEntityMock->method('getOrder')->willReturn($orderEntityMock);

        return $orderItemEntityMock;
    }
}
