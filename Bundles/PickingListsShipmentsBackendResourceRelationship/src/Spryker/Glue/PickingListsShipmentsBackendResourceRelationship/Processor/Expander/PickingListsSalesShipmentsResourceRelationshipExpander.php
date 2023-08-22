<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\Processor\Expander;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\Processor\Filter\PickingListItemResourceFilterInterface;
use Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\Processor\Reader\SalesShipmentResourceRelationshipReaderInterface;

class PickingListsSalesShipmentsResourceRelationshipExpander implements PickingListsSalesShipmentsResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\Processor\Filter\PickingListItemResourceFilterInterface
     */
    protected PickingListItemResourceFilterInterface $pickingListItemResourceFilter;

    /**
     * @var \Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\Processor\Reader\SalesShipmentResourceRelationshipReaderInterface
     */
    protected SalesShipmentResourceRelationshipReaderInterface $salesShipmentResourceRelationshipReader;

    /**
     * @param \Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\Processor\Filter\PickingListItemResourceFilterInterface $pickingListItemResourceFilter
     * @param \Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\Processor\Reader\SalesShipmentResourceRelationshipReaderInterface $salesShipmentResourceRelationshipReader
     */
    public function __construct(
        PickingListItemResourceFilterInterface $pickingListItemResourceFilter,
        SalesShipmentResourceRelationshipReaderInterface $salesShipmentResourceRelationshipReader
    ) {
        $this->pickingListItemResourceFilter = $pickingListItemResourceFilter;
        $this->salesShipmentResourceRelationshipReader = $salesShipmentResourceRelationshipReader;
    }

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return void
     */
    public function addPickingListItemsSalesShipmentsRelationships(
        array $glueResourceTransfers,
        GlueRequestTransfer $glueRequestTransfer
    ): void {
        $pickingListItemsResourceTransfers = $this->pickingListItemResourceFilter->filterPickingListItemResources($glueResourceTransfers);
        $orderItemUuids = $this->extractOrderItemUuids($pickingListItemsResourceTransfers);

        $salesShipmentRelationshipTransfers = $this->salesShipmentResourceRelationshipReader
            ->getSalesShipmentRelationshipsIndexedByOrderItemUuid($orderItemUuids);

        $this->addSalesShipmentRelationshipsToGlueResourceTransfers(
            $pickingListItemsResourceTransfers,
            $salesShipmentRelationshipTransfers,
        );
    }

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     *
     * @return list<string>
     */
    protected function extractOrderItemUuids(array $glueResourceTransfers): array
    {
        $orderItemUuids = [];
        foreach ($glueResourceTransfers as $glueResourceTransfer) {
            /** @var \Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer $pickingListItemsBackendApiAttributesTransfer */
            $pickingListItemsBackendApiAttributesTransfer = $glueResourceTransfer->getAttributes();
            $orderItemUuids[] = $pickingListItemsBackendApiAttributesTransfer->getOrderItemOrFail()->getUuidOrFail();
        }

        return $orderItemUuids;
    }

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     * @param array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer> $glueRelationshipTransfers
     *
     * @return void
     */
    protected function addSalesShipmentRelationshipsToGlueResourceTransfers(
        array $glueResourceTransfers,
        array $glueRelationshipTransfers
    ): void {
        foreach ($glueResourceTransfers as $glueResourceTransfer) {
            /** @var \Generated\Shared\Transfer\PickingListItemsBackendApiAttributesTransfer $pickingListItemsBackendApiAttributesTransfer */
            $pickingListItemsBackendApiAttributesTransfer = $glueResourceTransfer->getAttributes();
            $orderItemUuid = $pickingListItemsBackendApiAttributesTransfer->getOrderItemOrFail()->getUuidOrFail();

            $salesShipmentRelationshipTransfer = $glueRelationshipTransfers[$orderItemUuid] ?? null;

            if ($salesShipmentRelationshipTransfer) {
                $glueResourceTransfer->addRelationship($salesShipmentRelationshipTransfer);
            }
        }
    }
}
