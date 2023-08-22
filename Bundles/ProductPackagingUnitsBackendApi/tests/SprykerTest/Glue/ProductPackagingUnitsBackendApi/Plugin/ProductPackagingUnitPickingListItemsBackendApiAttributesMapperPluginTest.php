<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ProductPackagingUnitsBackendApi\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\PickingListItemBuilder;
use Generated\Shared\DataBuilder\PickingListItemsBackendApiAttributesBuilder;
use Generated\Shared\DataBuilder\ProductMeasurementSalesUnitBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderItemsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\PickingListItemTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Glue\ProductPackagingUnitsBackendApi\Plugin\PickingListsBackendApi\ProductPackagingUnitPickingListItemsBackendApiAttributesMapperPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ProductPackagingUnitsBackendApi
 * @group Plugin
 * @group ProductPackagingUnitPickingListItemsBackendApiAttributesMapperPluginTest
 * Add your own group annotations below this line
 */
class ProductPackagingUnitPickingListItemsBackendApiAttributesMapperPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testMapPickingListItemTransfersToPickingListItemsBackendApiAttributesTransfersShouldMapAmountSalesUnitData(): void
    {
        // Arrange
        $productMeasurementSalesUnitTransfer = (new ProductMeasurementSalesUnitBuilder([
            ProductMeasurementSalesUnitTransfer::CONVERSION => 1.0,
            ProductMeasurementSalesUnitTransfer::PRECISION => 1000,
        ]))->withProductMeasurementUnit()
            ->build();
        $itemTransfer = (new ItemBuilder([
            ItemTransfer::UUID => 'fake-uuid',
            ItemTransfer::AMOUNT => new Decimal(2),
            ItemTransfer::AMOUNT_SALES_UNIT => $productMeasurementSalesUnitTransfer->toArray(),
        ]))->build();
        $pickingListItemTransfer = (new PickingListItemBuilder([
            PickingListItemTransfer::ORDER_ITEM => $itemTransfer->toArray(),
        ]))->build();

        $pickingListItemsBackendApiAttributesTransfer = (new PickingListItemsBackendApiAttributesBuilder([
            PickingListItemsBackendApiAttributesTransfer::ORDER_ITEM => [
                OrderItemsBackendApiAttributesTransfer::UUID => $itemTransfer->getUuidOrFail(),
            ],
        ]))->build();

        // Act
        $pickingListItemsBackendApiAttributesTransfers = (new ProductPackagingUnitPickingListItemsBackendApiAttributesMapperPlugin())
            ->mapPickingListItemTransfersToPickingListItemsBackendApiAttributesTransfers(
                [$pickingListItemTransfer],
                [$pickingListItemsBackendApiAttributesTransfer],
            );

        // Assert
        $this->assertCount(1, $pickingListItemsBackendApiAttributesTransfers);
        $resultPickingListItemsBackendApiAttributesTransfer = $pickingListItemsBackendApiAttributesTransfers[0];
        $this->assertNotNull($resultPickingListItemsBackendApiAttributesTransfer->getOrderItem());
        $this->assertNotNull($resultPickingListItemsBackendApiAttributesTransfer->getOrderItem()->getAmountSalesUnit());
        $this->assertNotNull($resultPickingListItemsBackendApiAttributesTransfer->getOrderItem()->getAmount());

        $productMeasurementSalesUnitsBackendApiAttributesTransfer = $resultPickingListItemsBackendApiAttributesTransfer
            ->getOrderItemOrFail()
            ->getAmountSalesUnitOrFail();
        $this->assertSame(
            $productMeasurementSalesUnitTransfer->getConversionOrFail(),
            $productMeasurementSalesUnitsBackendApiAttributesTransfer->getConversion(),
        );
        $this->assertSame(
            $productMeasurementSalesUnitTransfer->getPrecisionOrFail(),
            $productMeasurementSalesUnitsBackendApiAttributesTransfer->getPrecision(),
        );
        $this->assertNotNull($productMeasurementSalesUnitsBackendApiAttributesTransfer->getProductMeasurementUnit());

        $productMeasurementUnitsBackendApiAttributesTransfer = $productMeasurementSalesUnitsBackendApiAttributesTransfer->getProductMeasurementUnitOrFail();
        $this->assertSame(
            $productMeasurementSalesUnitTransfer->getProductMeasurementUnitOrFail()->getNameOrFail(),
            $productMeasurementUnitsBackendApiAttributesTransfer->getName(),
        );
        $this->assertSame(
            $productMeasurementSalesUnitTransfer->getProductMeasurementUnitOrFail()->getCodeOrFail(),
            $productMeasurementUnitsBackendApiAttributesTransfer->getCode(),
        );
    }
}
