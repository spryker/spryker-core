<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPackagingUnitsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\ApiOrderItemsAttributesTransfer;
use Generated\Shared\Transfer\ApiProductMeasurementSalesUnitsAttributesTransfer;
use Generated\Shared\Transfer\ItemTransfer;

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
     * @param array<string, \Generated\Shared\Transfer\ApiPickingListItemsAttributesTransfer> $apiPickingListItemsAttributesTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\ApiPickingListItemsAttributesTransfer>
     */
    public function mapPickingListItemTransfersToApiPickingListItemsAttributesTransfers(
        array $pickingListItemTransfers,
        array $apiPickingListItemsAttributesTransfers
    ): array {
        $pickingListItemTransfersIndexedByOrderItemUuid = $this->getPickingListItemTransfersIndexedByOrderItemUuid($pickingListItemTransfers);
        foreach ($apiPickingListItemsAttributesTransfers as $apiPickingListItemsAttributesTransfer) {
            $pickingListItemTransfer = $pickingListItemTransfersIndexedByOrderItemUuid[$apiPickingListItemsAttributesTransfer->getOrderItemOrFail()->getUuidOrFail()] ?? null;
            if (!$pickingListItemTransfer) {
                continue;
            }

            $apiOrderItemsAttributesTransfer = $this->mapItemTransferToApiOrderItemsAttributesTransfer(
                $pickingListItemTransfer->getOrderItemOrFail(),
                $apiPickingListItemsAttributesTransfer->getOrderItemOrFail(),
            );

            $apiPickingListItemsAttributesTransfer->setOrderItem($apiOrderItemsAttributesTransfer);
        }

        return $apiPickingListItemsAttributesTransfers;
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
     * @param \Generated\Shared\Transfer\ApiOrderItemsAttributesTransfer $apiOrderItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ApiOrderItemsAttributesTransfer
     */
    protected function mapItemTransferToApiOrderItemsAttributesTransfer(
        ItemTransfer $itemTransfer,
        ApiOrderItemsAttributesTransfer $apiOrderItemsAttributesTransfer
    ): ApiOrderItemsAttributesTransfer {
        if (!$itemTransfer->getAmountSalesUnit() || !$itemTransfer->getAmount()) {
            return $apiOrderItemsAttributesTransfer;
        }

        $apiProductMeasurementSalesUnitsAttributesTransfer = $this->productMeasurementSalesUnitMapper->mapProductMeasurementSalesUnitTransferToApiProductMeasurementSalesUnitsAttributesTransfer(
            $itemTransfer->getAmountSalesUnit(),
            new ApiProductMeasurementSalesUnitsAttributesTransfer(),
        );

        return $apiOrderItemsAttributesTransfer
            ->setAmount($itemTransfer->getAmount())
            ->setAmountSalesUnit($apiProductMeasurementSalesUnitsAttributesTransfer);
    }
}
