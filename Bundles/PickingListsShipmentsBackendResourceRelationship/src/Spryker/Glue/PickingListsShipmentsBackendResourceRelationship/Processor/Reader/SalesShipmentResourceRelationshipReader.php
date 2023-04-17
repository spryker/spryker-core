<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\Processor\Reader;

use ArrayObject;
use Generated\Shared\Transfer\GlueRelationshipTransfer;
use Generated\Shared\Transfer\SalesShipmentConditionsTransfer;
use Generated\Shared\Transfer\SalesShipmentCriteriaTransfer;
use Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\Dependency\Resource\PickingListsShipmentsBackendResourceRelationshipToShipmentsBackendApiResourceInterface;

class SalesShipmentResourceRelationshipReader implements SalesShipmentResourceRelationshipReaderInterface
{
    /**
     * @var \Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\Dependency\Resource\PickingListsShipmentsBackendResourceRelationshipToShipmentsBackendApiResourceInterface
     */
    protected PickingListsShipmentsBackendResourceRelationshipToShipmentsBackendApiResourceInterface $shipmentsBackendApiResource;

    /**
     * @param \Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\Dependency\Resource\PickingListsShipmentsBackendResourceRelationshipToShipmentsBackendApiResourceInterface $shipmentsBackendApiResource
     */
    public function __construct(
        PickingListsShipmentsBackendResourceRelationshipToShipmentsBackendApiResourceInterface $shipmentsBackendApiResource
    ) {
        $this->shipmentsBackendApiResource = $shipmentsBackendApiResource;
    }

    /**
     * @param list<string> $orderItemUuids
     *
     * @return array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer>
     */
    public function getSalesShipmentRelationshipsIndexedByOrderItemUuid(array $orderItemUuids): array
    {
        $salesShipmentCriteriaTransfer = $this->createSalesShipmentCriteriaTransfer($orderItemUuids);
        $salesShipmentResourceCollectionTransfer = $this->shipmentsBackendApiResource->getSalesShipmentResourceCollection($salesShipmentCriteriaTransfer);

        $orderItemUuidsGroupedBySalesShipmentUuids = $this->getOrderItemUuidsGroupedBySalesShipmentUuids(
            $salesShipmentResourceCollectionTransfer->getShipments(),
        );

        $salesShipmentRelationshipTransfers = [];
        foreach ($salesShipmentResourceCollectionTransfer->getSalesShipmentResources() as $salesShipmentResourceTransfer) {
            if (!isset($orderItemUuidsGroupedBySalesShipmentUuids[$salesShipmentResourceTransfer->getIdOrFail()])) {
                continue;
            }

            foreach ($orderItemUuidsGroupedBySalesShipmentUuids[$salesShipmentResourceTransfer->getIdOrFail()] as $orderItemUuid) {
                $salesShipmentRelationshipTransfer = (new GlueRelationshipTransfer())->addResource($salesShipmentResourceTransfer);
                $salesShipmentRelationshipTransfers[$orderItemUuid] = $salesShipmentRelationshipTransfer;
            }
        }

        return $salesShipmentRelationshipTransfers;
    }

    /**
     * @param list<string> $orderItemUuids
     *
     * @return \Generated\Shared\Transfer\SalesShipmentCriteriaTransfer
     */
    protected function createSalesShipmentCriteriaTransfer(array $orderItemUuids): SalesShipmentCriteriaTransfer
    {
        $salesShipmentConditionsTransfer = (new SalesShipmentConditionsTransfer())
            ->setOrderItemUuids($orderItemUuids)
            ->setWithOrderItems(true);

        return (new SalesShipmentCriteriaTransfer())->setSalesShipmentConditions($salesShipmentConditionsTransfer);
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTransfer> $shipmentTransfers
     *
     * @return array<string, list<string>>
     */
    protected function getOrderItemUuidsGroupedBySalesShipmentUuids(ArrayObject $shipmentTransfers): array
    {
        $orderItemUuidsGroupedBySalesShipmentUuids = [];
        foreach ($shipmentTransfers as $shipmentTransfer) {
            $salesShipmentUuid = $shipmentTransfer->getUuidOrFail();
            foreach ($shipmentTransfer->getOrderItems() as $itemTransfer) {
                $orderItemUuidsGroupedBySalesShipmentUuids[$salesShipmentUuid][] = $itemTransfer->getUuidOrFail();
            }
        }

        return $orderItemUuidsGroupedBySalesShipmentUuids;
    }
}
