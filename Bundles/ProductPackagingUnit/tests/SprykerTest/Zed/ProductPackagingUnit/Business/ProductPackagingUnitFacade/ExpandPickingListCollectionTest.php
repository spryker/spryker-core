<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPackagingUnit\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListItemTransfer;
use Generated\Shared\Transfer\PickingListTransfer;
use SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductPackagingUnit
 * @group Business
 * @group ExpandPickingListCollectionTest
 * Add your own group annotations below this line
 */
class ExpandPickingListCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductPackagingUnit\ProductPackagingUnitBusinessTester
     */
    protected ProductPackagingUnitBusinessTester $tester;

    /**
     * @return void
     */
    public function testExpandPickingListCollectionShouldReturnPickingListCollectionWithAmountSalesUnits(): void
    {
        // Arrange
        $salesOrderEntity = $this->tester->haveSalesOrderEntity();
        $productMeasurementUnit = $this->tester->haveProductMeasurementUnit();

        $pickingListItemTransfers = new ArrayObject();
        foreach ($salesOrderEntity->getItems() as $salesOrderItemEntity) {
            $salesOrderItemEntity->setAmountMeasurementUnitName($productMeasurementUnit->getNameOrFail());
            $salesOrderItemEntity->save();

            $pickingListItemTransfers->append(
                (new PickingListItemTransfer())->setOrderItem(
                    (new ItemTransfer())->fromArray($salesOrderItemEntity->toArray(), true),
                ),
            );
        }

        $pickingListTransfer = (new PickingListTransfer())->setPickingListItems($pickingListItemTransfers);
        $pickingListCollectionTransfer = (new PickingListCollectionTransfer())->addPickingList($pickingListTransfer);

        // Act
        $pickingListCollectionTransfer = $this->tester->getFacade()->expandPickingListCollection($pickingListCollectionTransfer);

        // Assert
        $this->assertCount(1, $pickingListCollectionTransfer->getPickingLists());

        /** @var \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer */
        $pickingListTransfer = $pickingListCollectionTransfer->getPickingLists()->getIterator()->current();
        $this->assertCount($salesOrderEntity->getItems()->count(), $pickingListTransfer->getPickingListItems());

        /** @var \Generated\Shared\Transfer\PickingListItemTransfer $pickingListItemTransfer */
        $pickingListItemTransfer = $pickingListTransfer->getPickingListItems()->getIterator()->current();
        $this->assertNotEmpty($pickingListItemTransfer->getOrderItem());
        $this->assertNotEmpty($pickingListItemTransfer->getOrderItem()->getAmountSalesUnit());
        $this->assertNotEmpty($pickingListItemTransfer->getOrderItem()->getAmountSalesUnit()->getProductMeasurementUnit());
        $this->assertSame($productMeasurementUnit->getNameOrFail(), $pickingListItemTransfer->getOrderItem()->getAmountSalesUnit()->getProductMeasurementUnit()->getName());
    }
}
