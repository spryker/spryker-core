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
     * @return void
     */
    protected function _setUp(): void
    {
        parent::_setUp();

        $this->tester->configureTestStateMachine([BusinessHelper::DEFAULT_OMS_PROCESS_NAME]);
        $this->saveOrderTransfer = $this->tester->haveOrder([], BusinessHelper::DEFAULT_OMS_PROCESS_NAME);
    }

    /**
     * @return void
     */
    public function testSaveOrderWithValidOrderCustomReferenceLength(): void
    {
        // Act
        $this->tester->getFacade()->saveOrderCustomReference(
            static::ORDER_CUSTOM_REFERENCE,
            $this->saveOrderTransfer->getIdSalesOrder()
        );

        $orderTransfer = $this->findOrder($this->saveOrderTransfer);

        // Assert
        $this->assertEquals(static::ORDER_CUSTOM_REFERENCE, $orderTransfer->getOrderCustomReference());
    }

    /**
     * @return void
     */
    public function testSaveOrderWithEmptyOrderCustomReferenceLength(): void
    {
        // Act
        $this->tester->getFacade()->saveOrderCustomReference(
            static::ORDER_CUSTOM_REFERENCE,
            $this->saveOrderTransfer->getIdSalesOrder()
        );

        $this->tester->getFacade()->saveOrderCustomReference(
            '',
            $this->saveOrderTransfer->getIdSalesOrder()
        );

        $orderTransfer = $this->findOrder($this->saveOrderTransfer);

        // Assert
        $this->assertEquals(static::ORDER_CUSTOM_REFERENCE, $orderTransfer->getOrderCustomReference());
    }

    /**
     * @return void
     */
    public function testGetOrderCustomReferenceQuoteFieldsAllowedForSaving()
    {
        // Act
        $orderCustomReferenceQuoteFieldsAllowedForSaving = $this->tester->getFacade()
            ->getOrderCustomReferenceQuoteFieldsAllowedForSaving(new QuoteTransfer());

        // Assert
        $this->assertTrue(
            in_array(
                QuoteTransfer::ORDER_CUSTOM_REFERENCE,
                $orderCustomReferenceQuoteFieldsAllowedForSaving
            )
        );
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
