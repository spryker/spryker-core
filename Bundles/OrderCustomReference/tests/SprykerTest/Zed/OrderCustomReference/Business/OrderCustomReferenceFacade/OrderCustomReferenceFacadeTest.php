<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\OrderCustomReference\Business\OrderCustomReferenceFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use SprykerTest\Zed\Sales\Helper\BusinessHelper;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group OrderCustomReference
 * @group Business
 * @group OrderCustomReferenceFacade
 * @group Facade
 * @group OrderCustomReferenceFacadeTest
 * Add your own group annotations below this line
 */
class OrderCustomReferenceFacadeTest extends Unit
{
    protected const ORDER_CUSTOM_REFERENCE = 'ORDER_CUSTOM_REFERENCE';

    /**
     * @var \SprykerTest\Zed\OrderCustomReference\OrderCustomReferenceBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\SaveOrderTransfer
     */
    protected $saveOrderTransfer;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @var \Generated\Shared\Transfer\OrderTransfer
     */
    protected $orderTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);
        $this->saveOrderTransfer = $this->tester->haveOrder([], BusinessHelper::DEFAULT_OMS_PROCESS_NAME);
        $this->quoteTransfer = (new QuoteTransfer())->setOrderCustomReference(static::ORDER_CUSTOM_REFERENCE);
        $this->orderTransfer = (new OrderTransfer())->setIdSalesOrder($this->saveOrderTransfer->getIdSalesOrder());
    }

    /**
     * @return void
     */
    public function testSaveOrderFromQuoteWithValidOrderCustomReferenceLength(): void
    {
        // Act
        $orderCustomReferenceResponseTransfer = $this->tester->getFacade()
            ->saveOrderCustomReferenceFromQuote(
                $this->quoteTransfer,
                $this->saveOrderTransfer
            );

        // Assert
        $this->assertTrue($orderCustomReferenceResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testSaveOrderFromQuoteWithEmptyOrderCustomReferenceLength(): void
    {
        // Arrange
        $this->tester->getFacade()
            ->saveOrderCustomReferenceFromQuote(
                $this->quoteTransfer,
                $this->saveOrderTransfer
            );

        // Act
        $this->tester->getFacade()
            ->saveOrderCustomReferenceFromQuote(
                (new QuoteTransfer()),
                $this->saveOrderTransfer
            );

        $orderTransfer = $this->findOrder($this->saveOrderTransfer);

        // Assert
        $this->assertSame($this->quoteTransfer->getOrderCustomReference(), $orderTransfer->getOrderCustomReference());
    }

    /**
     * @return void
     */
    public function testUpdateOrderCustomReferenceWithoutIdSalesOrder(): void
    {
        // Act
        $orderCustomReferenceResponseTransfer = $this->tester->getFacade()->updateOrderCustomReference(
            static::ORDER_CUSTOM_REFERENCE,
            $this->orderTransfer->setIdSalesOrder(null)
        );

        // Assert
        $this->assertFalse($orderCustomReferenceResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testUpdateOrderCustomReference(): void
    {
        // Act
        $orderCustomReferenceResponseTransfer = $this->tester->getFacade()->updateOrderCustomReference(
            static::ORDER_CUSTOM_REFERENCE,
            $this->orderTransfer
        );

        // Assert
        $this->assertTrue($orderCustomReferenceResponseTransfer->getIsSuccessful());
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function findOrder(SaveOrderTransfer $saveOrderTransfer): OrderTransfer
    {
        return $this->tester->getLocator()
            ->sales()
            ->facade()
            ->getOrderByIdSalesOrder($saveOrderTransfer->getIdSalesOrder());
    }
}
