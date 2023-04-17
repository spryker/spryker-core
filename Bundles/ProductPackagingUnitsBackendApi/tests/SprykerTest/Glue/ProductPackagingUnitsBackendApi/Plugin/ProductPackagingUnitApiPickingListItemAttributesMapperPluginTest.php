<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ProductPackagingUnitsBackendApi\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ApiPickingListItemsAttributesBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\PickingListItemBuilder;
use Generated\Shared\DataBuilder\ProductMeasurementSalesUnitBuilder;
use Generated\Shared\Transfer\ApiOrderItemsAttributesTransfer;
use Generated\Shared\Transfer\ApiPickingListItemsAttributesTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PickingListItemTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Glue\ProductPackagingUnitsBackendApi\Plugin\PickingListsBackendApi\ProductPackagingUnitApiPickingListItemsAttributesMapperPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ProductPackagingUnitsBackendApi
 * @group Plugin
 * @group ProductPackagingUnitApiPickingListItemAttributesMapperPluginTest
 * Add your own group annotations below this line
 */
class ProductPackagingUnitApiPickingListItemAttributesMapperPluginTest extends Unit
{
    /**
     * @return void
     */
    public function testMapPickingListItemTransfersToApiPickingListItemsAttributesTransfersShouldMapAmountSalesUnitData(): void
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

        $apiPickingListItemsAttributesTransfer = (new ApiPickingListItemsAttributesBuilder([
            ApiPickingListItemsAttributesTransfer::ORDER_ITEM => [
                ApiOrderItemsAttributesTransfer::UUID => $itemTransfer->getUuidOrFail(),
            ],
        ]))->build();

        // Act
        $apiPickingListItemsAttributesTransfers = (new ProductPackagingUnitApiPickingListItemsAttributesMapperPlugin())
            ->mapPickingListItemTransfersToApiPickingListItemsAttributesTransfers(
                [$pickingListItemTransfer],
                [$apiPickingListItemsAttributesTransfer],
            );

        // Assert
        $this->assertCount(1, $apiPickingListItemsAttributesTransfers);
        $resultApiPickingListItemsAttributesTransfer = $apiPickingListItemsAttributesTransfers[0];
        $this->assertNotNull($resultApiPickingListItemsAttributesTransfer->getOrderItem());
        $this->assertNotNull($resultApiPickingListItemsAttributesTransfer->getOrderItem()->getAmountSalesUnit());
        $this->assertNotNull($resultApiPickingListItemsAttributesTransfer->getOrderItem()->getAmount());

        $apiProductMeasurementSalesUnitsAttributesTransfer = $resultApiPickingListItemsAttributesTransfer
            ->getOrderItemOrFail()
            ->getAmountSalesUnitOrFail();
        $this->assertSame(
            $productMeasurementSalesUnitTransfer->getConversionOrFail(),
            $apiProductMeasurementSalesUnitsAttributesTransfer->getConversion(),
        );
        $this->assertSame(
            $productMeasurementSalesUnitTransfer->getPrecisionOrFail(),
            $apiProductMeasurementSalesUnitsAttributesTransfer->getPrecision(),
        );
        $this->assertNotNull($apiProductMeasurementSalesUnitsAttributesTransfer->getProductMeasurementUnit());

        $apiProductMeasurementUnitsAttributesTransfer = $apiProductMeasurementSalesUnitsAttributesTransfer->getProductMeasurementUnitOrFail();
        $this->assertSame(
            $productMeasurementSalesUnitTransfer->getProductMeasurementUnitOrFail()->getNameOrFail(),
            $apiProductMeasurementUnitsAttributesTransfer->getName(),
        );
        $this->assertSame(
            $productMeasurementSalesUnitTransfer->getProductMeasurementUnitOrFail()->getCodeOrFail(),
            $apiProductMeasurementUnitsAttributesTransfer->getCode(),
        );
    }
}
