<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantSalesOrder\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use SprykerTest\Zed\MerchantSalesOrder\MerchantSalesOrderBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantSalesOrder
 * @group Business
 * @group Facade
 * @group UpdateMerchantOrderTotalsTest
 * Add your own group annotations below this line
 */
class UpdateMerchantOrderTotalsTest extends Unit
{
    /**
     * @var int
     */
    protected const FAKE_ID_SALES_ORDER = 12345;

    /**
     * @var int
     */
    protected const FAKE_GRAND_TOTAL_VALUE = 2500;

    /**
     * @var array<string>
     */
    protected const FAKE_MERCHANT_REFERENCES = ['FAKE_REFERENCE_1', 'FAKE_REFERENCE_2'];

    /**
     * @var \SprykerTest\Zed\MerchantSalesOrder\MerchantSalesOrderBusinessTester
     */
    protected MerchantSalesOrderBusinessTester $tester;

    /**
     * @return void
     */
    public function testUpdateMerchantOrderTotalShouldAssertRequiredIdSalesOrder(): void
    {
        // Arrange
        $orderTransfer = (new OrderTransfer())
            ->setMerchantReferences(static::FAKE_MERCHANT_REFERENCES)
            ->setTotals((new TotalsTransfer()));

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateMerchantOrderTotals($orderTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateMerchantOrderTotalShouldAssertRequiredTotals(): void
    {
        // Arrange
        $orderTransfer = (new OrderTransfer())
            ->setMerchantReferences(static::FAKE_MERCHANT_REFERENCES)
            ->setIdSalesOrder(static::FAKE_ID_SALES_ORDER);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateMerchantOrderTotals($orderTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateMerchantOrderTotalShouldExpectNotEmptyMerchantReferences(): void
    {
        // Arrange
        $merchantOrderTransfer = $this->tester->createMerchantOrder();

        $orderTransfer = (new OrderTransfer())
            ->setIdSalesOrder($merchantOrderTransfer->getIdOrder())
            ->setTotals((new TotalsTransfer())->setGrandTotal(static::FAKE_GRAND_TOTAL_VALUE))
            ->setMerchantReferences([]);

        // Act
        $this->tester->getFacade()->updateMerchantOrderTotals($orderTransfer);

        // Assert
        $this->assertSame(
            $merchantOrderTransfer->getTotals()->getGrandTotal(),
            $this->tester->getPersistedMerchantOrderGrandTotal($merchantOrderTransfer),
        );
    }

    /**
     * @return void
     */
    public function testUpdateMerchantOrderTotalShouldExpectNotNullableMerchantReferences(): void
    {
        // Arrange
        $merchantOrderTransfer = $this->tester->createMerchantOrder();

        $orderTransfer = (new OrderTransfer())
            ->setIdSalesOrder($merchantOrderTransfer->getIdOrder())
            ->setTotals((new TotalsTransfer())->setGrandTotal(static::FAKE_GRAND_TOTAL_VALUE))
            ->setMerchantReferences(null);

        // Act
        $this->tester->getFacade()->updateMerchantOrderTotals($orderTransfer);

        // Assert
        $this->assertSame(
            $merchantOrderTransfer->getTotals()->getGrandTotal(),
            $this->tester->getPersistedMerchantOrderGrandTotal($merchantOrderTransfer),
        );
    }

    /**
     * @return void
     */
    public function testUpdateMerchantOrderTotalShouldUpdateMerchantOrderTotals(): void
    {
        // Arrange
        $merchantOrderTransfer = $this->tester->createMerchantOrder();
        $totalTransfer = $merchantOrderTransfer->getTotals();

        $orderTransfer = (new OrderTransfer())
            ->setIdSalesOrder($merchantOrderTransfer->getIdOrder())
            ->setTotals($totalTransfer->setGrandTotal(static::FAKE_GRAND_TOTAL_VALUE))
            ->addMerchantReference($merchantOrderTransfer->getMerchantReference());

        // Act
        $this->tester->getFacade()->updateMerchantOrderTotals($orderTransfer);

        // Assert
        $this->assertSame(static::FAKE_GRAND_TOTAL_VALUE, $this->tester->getPersistedMerchantOrderGrandTotal($merchantOrderTransfer));
    }
}
