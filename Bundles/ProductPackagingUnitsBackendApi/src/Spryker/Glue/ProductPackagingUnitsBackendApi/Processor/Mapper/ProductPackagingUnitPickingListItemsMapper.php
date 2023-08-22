<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPackagingUnitsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderItemsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitsBackendApiAttributesTransfer;

class ProductPackagingUnitPickingListItemsMapper implements ProductPackagingUnitPickingListItemsMapperInterface
{
    /**
     * @var \Spryker\Glue\ProductPackagingUnitsBackendApi\Processor\Mapper\ProductMeasurementSalesUnitMapperInterface
     */
    protected ProductMeasurementSalesUnitMapperInterface $productMeasurementSalesUnitMapper;

    /**
     * @param \Spryker\Glue\ProductPackagingUnitsBackendApi\Processor\Mapper\ProductMeasurementSalesUnitMapperInterface $productMeasurementSalesUnitMapper
     */
    public function __construct(ProductMeasurementSalesUnitMapperInterface $productMeasurementSalesUnitMapper)
    {
        $this->productMeasurementSalesUnitMapper = $productMeasurementSalesUnitMapper;
    }

    /**
     * @param list<\Generated\Shared\Transfer\PickingListItemTransfer> $pickingListItemTransfers
     * @param array<string, \Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer> $pickingListItemsBackendApiAttributesTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer>
     */
    public function mapPickingListItemTransfersToPickingListItemsBackendApiAttributesTransfers(
        array $pickingListItemTransfers,
        array $pickingListItemsBackendApiAttributesTransfers
    ): array {
        $pickingListItemTransfersIndexedByOrderItemUuid = $this->getPickingListItemTransfersIndexedByOrderItemUuid($pickingListItemTransfers);
        foreach ($pickingListItemsBackendApiAttributesTransfers as $pickingListItemsBackendApiAttributesTransfer) {
            $pickingListItemTransfer = $pickingListItemTransfersIndexedByOrderItemUuid[$pickingListItemsBackendApiAttributesTransfer->getOrderItemOrFail()->getUuidOrFail()] ?? null;
            if (!$pickingListItemTransfer) {
                continue;
            }

            $orderItemsBackendApiAttributesTransfer = $this->mapItemTransferToOrderItemsBackendApiAttributesTransfer(
                $pickingListItemTransfer->getOrderItemOrFail(),
                $pickingListItemsBackendApiAttributesTransfer->getOrderItemOrFail(),
            );

            $pickingListItemsBackendApiAttributesTransfer->setOrderItem($orderItemsBackendApiAttributesTransfer);
        }

        return $pickingListItemsBackendApiAttributesTransfers;
    }

    /**
     * @param list<\Generated\Shared\Transfer\PickingListItemTransfer> $pickingListItemTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\PickingListItemTransfer>
     */
    protected function getPickingListItemTransfersIndexedByOrderItemUuid(array $pickingListItemTransfers): array
    {
        $pickingListItemTransfersIndexedByOrderItemUuid = [];
        foreach ($pickingListItemTransfers as $pickingListItemTransfer) {
            $pickingListItemTransfersIndexedByOrderItemUuid[$pickingListItemTransfer->getOrderItemOrFail()->getUuidOrFail()] = $pickingListItemTransfer;
        }

        return $pickingListItemTransfersIndexedByOrderItemUuid;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\OrderItemsBackendApiAttributesTransfer $orderItemsBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\OrderItemsBackendApiAttributesTransfer
     */
    protected function mapItemTransferToOrderItemsBackendApiAttributesTransfer(
        ItemTransfer $itemTransfer,
        OrderItemsBackendApiAttributesTransfer $orderItemsBackendApiAttributesTransfer
    ): OrderItemsBackendApiAttributesTransfer {
        if (!$itemTransfer->getAmountSalesUnit() || !$itemTransfer->getAmount()) {
            return $orderItemsBackendApiAttributesTransfer;
        }

        $productMeasurementSalesUnitsBackendApiAttributesTransfer = $this->productMeasurementSalesUnitMapper->mapProductMeasurementSalesUnitTransferToProductMeasurementSalesUnitsBackendApiAttributesTransfer(
            $itemTransfer->getAmountSalesUnit(),
            new ProductMeasurementSalesUnitsBackendApiAttributesTransfer(),
        );

        return $orderItemsBackendApiAttributesTransfer
            ->setAmount($itemTransfer->getAmount())
            ->setAmountSalesUnit($productMeasurementSalesUnitsBackendApiAttributesTransfer);
    }
}
