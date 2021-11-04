<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantSalesOrder\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantSalesOrder
 * @group Business
 * @group Facade
 * @group MerchantSalesOrderFacadePluginMethodsTest
 * Add your own group annotations below this line
 */
class MerchantSalesOrderFacadePluginMethodsTest extends Unit
{
    /**
     * @uses \Spryker\Shared\Shipment\ShipmentConfig::SHIPMENT_EXPENSE_TYPE.
     *
     * @var string
     */
    protected const VALID_SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * @var string
     */
    protected const INVALID_SHIPMENT_EXPENSE_TYPE = 'ANOTHER_EXPENSE_TYPE';

    /**
     * @var string
     */
    protected const TEST_MERCHANT_REFERENCE = 'test-merchant-reference';

    /**
     * @var string
     */
    protected const TEST_SECOND_MERCHANT_REFERENCE = 'test-second-merchant-reference';

    /**
     * @var \SprykerTest\Zed\MerchantSalesOrder\MerchantSalesOrderBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExpandOrderItemWithMerchantReturnsUpdatedTransferWithCorrectData(): void
    {
        // Arrange
        $itemTransfer = $this->tester->getItemTransfer([
            ItemTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
        ]);
        $salesOrderItemEntityTransfer = new SpySalesOrderItemEntityTransfer();

        // Act
        $newSalesOrderItemEntityTransfer = $this->tester
            ->getFacade()
            ->expandOrderItemWithMerchant($salesOrderItemEntityTransfer, $itemTransfer);

        // Assert
        $this->assertSame(static::TEST_MERCHANT_REFERENCE, $newSalesOrderItemEntityTransfer->getMerchantReference());
    }

    /**
     * @return void
     */
    public function testExpandOrderItemWithMerchantDoesNothingWithIncorrectData(): void
    {
        // Arrange
        $itemTransfer = $this->tester->getItemTransfer([ItemTransfer::MERCHANT_REFERENCE => null]);
        $salesOrderItemEntityTransfer = new SpySalesOrderItemEntityTransfer();

        // Act
        $newSalesOrderItemEntityTransfer = $this->tester
            ->getFacade()
            ->expandOrderItemWithMerchant($salesOrderItemEntityTransfer, $itemTransfer);

        // Assert
        $this->assertNull($newSalesOrderItemEntityTransfer->getMerchantReference());
    }

    /**
     * @return void
     */
    public function testExpandShipmentExpenseWithMerchantReferenceReturnsUpdatedTransfer(): void
    {
        // Arrange
        $expenseTransfer = (new ExpenseTransfer())->setType(static::VALID_SHIPMENT_EXPENSE_TYPE);
        $itemTransfer = (new ItemTransfer())->setMerchantReference(static::TEST_MERCHANT_REFERENCE);
        $shipmentGroupTransfer = (new ShipmentGroupTransfer())->addItem($itemTransfer);

        // Act
        $expenseTransfer = $this->tester
            ->getFacade()
            ->expandShipmentExpenseWithMerchantReference($expenseTransfer, $shipmentGroupTransfer);

        // Assert
        $this->assertSame($expenseTransfer->getMerchantReference(), $itemTransfer->getMerchantReference());
    }

    /**
     * @dataProvider getExpandShipmentExpenseWithMerchantReferenceNegativeScenarioDataProvider
     *
     * @param array $itemTransfersData
     * @param string $expenseType
     *
     * @return void
     */
    public function testExpandShipmentExpenseWithMerchantReferenceDoesNothingWithIncorrectData(
        array $itemTransfersData,
        string $expenseType
    ): void {
        // Arrange
        $expenseTransfer = (new ExpenseTransfer())->setType($expenseType);
        $shipmentGroupTransfer = new ShipmentGroupTransfer();
        foreach ($itemTransfersData as $itemTransferData) {
            $shipmentGroupTransfer->addItem((new ItemTransfer())->fromArray($itemTransferData));
        }

        // Act
        $expenseTransfer = $this->tester
            ->getFacade()
            ->expandShipmentExpenseWithMerchantReference($expenseTransfer, $shipmentGroupTransfer);

        // Assert
        $this->assertNull($expenseTransfer->getMerchantReference());
    }

    /**
     * @return array
     */
    public function getExpandShipmentExpenseWithMerchantReferenceNegativeScenarioDataProvider(): array
    {
        return [
            'with incorrect expense type' => [
                'itemTransfersData' => [
                    [
                        ExpenseTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
                    ],
                ],
                'expenseType' => static::INVALID_SHIPMENT_EXPENSE_TYPE,
            ],
            'with different merchant references' => [
                'itemTransfersData' => [
                    [
                        ExpenseTransfer::MERCHANT_REFERENCE => static::TEST_MERCHANT_REFERENCE,
                    ],
                    [
                        ExpenseTransfer::MERCHANT_REFERENCE => static::TEST_SECOND_MERCHANT_REFERENCE,
                    ],
                ],
                'expenseType' => static::VALID_SHIPMENT_EXPENSE_TYPE,
            ],
        ];
    }
}
