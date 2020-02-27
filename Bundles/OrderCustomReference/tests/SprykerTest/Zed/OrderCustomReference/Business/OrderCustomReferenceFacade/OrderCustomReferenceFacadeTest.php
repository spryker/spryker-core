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
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);
        $this->saveOrderTransfer = $this->tester->haveOrder([], BusinessHelper::DEFAULT_OMS_PROCESS_NAME);
        $this->quoteTransfer = (new QuoteTransfer())->setOrderCustomReference(static::ORDER_CUSTOM_REFERENCE);
    }

    /**
     * @return void
     */
    public function testSaveOrderWithValidOrderCustomReferenceLength(): void
    {
        // Act
        $this->tester->getFacade()->saveOrderCustomReference(
            $this->quoteTransfer,
            $this->saveOrderTransfer
        );

        $orderTransfer = $this->findOrder($this->saveOrderTransfer);

        // Assert
        $this->assertEquals($this->quoteTransfer->getOrderCustomReference(), $orderTransfer->getOrderCustomReference());
    }

    /**
     * @return void
     */
    public function testSaveOrderWithEmptyOrderCustomReferenceLength(): void
    {
        // Arrange
        $this->tester->getFacade()->saveOrderCustomReference(
            $this->quoteTransfer,
            $this->saveOrderTransfer
        );

        // Act
        $this->tester->getFacade()->saveOrderCustomReference(
            (new QuoteTransfer()),
            $this->saveOrderTransfer
        );

        $orderTransfer = $this->findOrder($this->saveOrderTransfer);

        // Assert
        $this->assertEquals($this->quoteTransfer->getOrderCustomReference(), $orderTransfer->getOrderCustomReference());
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
