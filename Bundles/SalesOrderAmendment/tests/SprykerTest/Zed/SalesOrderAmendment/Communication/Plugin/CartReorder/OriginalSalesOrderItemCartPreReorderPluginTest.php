<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendment\Communication\Plugin\CartReorder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Service\SalesOrderAmendment\SalesOrderAmendmentServiceInterface;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\SalesOrderAmendment\Communication\Plugin\CartReorder\OriginalSalesOrderItemCartPreReorderPlugin;
use Spryker\Zed\SalesOrderAmendment\SalesOrderAmendmentDependencyProvider;
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
 * @group OriginalSalesOrderItemCartPreReorderPluginTest
 * Add your own group annotations below this line
 */
class OriginalSalesOrderItemCartPreReorderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_ORDER_REFERENCE = 'DE--123';

    /**
     * @var string
     */
    protected const ORIGINAL_SALES_ORDER_ITEM_GROUP_KEY = 'SKU-1';

    /**
     * @var \SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentCommunicationTester
     */
    protected SalesOrderAmendmentCommunicationTester $tester;

    /**
     * @return void
     */
    public function testShouldNotSetOriginalSalesOrderItemsToQuoteWhenIsAmendmentFalse(): void
    {
        // Arrange
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setOrderReference(static::FAKE_ORDER_REFERENCE)
            ->setIsAmendment(false);

        $cartReorderTransfer = (new CartReorderTransfer())
            ->setQuote(new QuoteTransfer());

        // Act
        $cartReorderTransfer = (new OriginalSalesOrderItemCartPreReorderPlugin())
            ->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);

        // Assert
        $this->assertCount(0, $cartReorderTransfer->getQuote()->getOriginalSalesOrderItems());
    }

    /**
     * @return void
     */
    public function testShouldNotSetOriginalSalesOrderItemsToQuoteWhenIsAmendmentNull(): void
    {
        // Arrange
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setOrderReference(static::FAKE_ORDER_REFERENCE);

        $cartReorderTransfer = (new CartReorderTransfer())
            ->setQuote(new QuoteTransfer());

        // Act
        $cartReorderTransfer = (new OriginalSalesOrderItemCartPreReorderPlugin())
            ->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);

        // Assert
        $this->assertCount(0, $cartReorderTransfer->getQuote()->getOriginalSalesOrderItems());
    }

    /**
     * @return void
     */
    public function testShouldNotSetOriginalSalesOrderItemsToQuoteWhenNoItemsAreProvidedInOrder(): void
    {
        // Arrange
        $orderTransfer = (new OrderTransfer());
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setOrderReference(static::FAKE_ORDER_REFERENCE)
            ->setIsAmendment(true);

        $cartReorderTransfer = (new CartReorderTransfer())
            ->setQuote(new QuoteTransfer())
            ->setOrder($orderTransfer);

        // Act
        $cartReorderTransfer = (new OriginalSalesOrderItemCartPreReorderPlugin())
            ->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);

        // Assert
        $this->assertCount(0, $cartReorderTransfer->getQuote()->getOriginalSalesOrderItems());
    }

    /**
     * @return void
     */
    public function testShouldThrowNullValueExceptionWhenQuoteIsNotProvided(): void
    {
        // Arrange
        $orderTransfer = (new OrderTransfer())
            ->addItem((new ItemTransfer())->setSku('SKU-1'));
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setOrderReference(static::FAKE_ORDER_REFERENCE)
            ->setIsAmendment(true);

        $cartReorderTransfer = (new CartReorderTransfer())
            ->setOrder($orderTransfer);

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "quote" of transfer `Generated\Shared\Transfer\CartReorderTransfer` is null.');

        // Act
        (new OriginalSalesOrderItemCartPreReorderPlugin())->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowNullValueExceptionWhenOrderIsNotProvided(): void
    {
        // Arrange
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setIsAmendment(true);

        $cartReorderTransfer = (new CartReorderTransfer())
            ->setQuote(new QuoteTransfer());

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "order" of transfer `Generated\Shared\Transfer\CartReorderTransfer` is null.');

        // Act
        (new OriginalSalesOrderItemCartPreReorderPlugin())->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);
    }

    /**
     * @return void
     */
    public function testShouldSetOriginalSalesOrderItemsToQuote(): void
    {
        // Arrange
        $orderTransfer = (new OrderTransfer())
            ->addItem((new ItemTransfer())->setSku('SKU-1'));

        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setOrderReference(static::FAKE_ORDER_REFERENCE)
            ->setIsAmendment(true);

        $cartReorderTransfer = (new CartReorderTransfer())
            ->setQuote(new QuoteTransfer())
            ->setOrder($orderTransfer);
        $this->createSalesOrderAmendmentServiceMock();

        // Act
        $cartReorderTransfer = (new OriginalSalesOrderItemCartPreReorderPlugin())
            ->preReorder($cartReorderRequestTransfer, $cartReorderTransfer);

        // Assert
        $this->assertCount(1, $cartReorderTransfer->getQuote()->getOriginalSalesOrderItems());
        $this->assertSame($cartReorderTransfer->getOrder()->getItems()[0]->getSku(), $cartReorderTransfer->getQuote()->getOriginalSalesOrderItems()[0]->getSku());
        $this->assertSame($cartReorderTransfer->getOrder()->getItems()[0]->getQuantity(), $cartReorderTransfer->getQuote()->getOriginalSalesOrderItems()[0]->getQuantity());
        $this->assertSame(static::ORIGINAL_SALES_ORDER_ITEM_GROUP_KEY, $cartReorderTransfer->getQuote()->getOriginalSalesOrderItems()[0]->getGroupKey());
    }

    /**
     * @return void
     */
    protected function createSalesOrderAmendmentServiceMock(): void
    {
        $salesOrderAmendmentServiceMock = $this->getMockBuilder(SalesOrderAmendmentServiceInterface::class)
            ->getMock();
        $salesOrderAmendmentServiceMock->method('buildOriginalSalesOrderItemGroupKey')
            ->willReturn(static::ORIGINAL_SALES_ORDER_ITEM_GROUP_KEY);
        $this->tester->setDependency(SalesOrderAmendmentDependencyProvider::SERVICE_SALES_ORDER_AMENDMENT, $salesOrderAmendmentServiceMock);
    }
}
