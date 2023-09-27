<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsWarehousesBackendApi\Processor\Expander;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\PickingListsWarehousesBackendApi\Processor\Filter\PickingListResourceFilterInterface;
use Spryker\Glue\PickingListsWarehousesBackendApi\Processor\Reader\PickingListWarehouseResourceRelationshipReaderInterface;

class PickingListWarehouseResourceRelationshipExpander implements PickingListWarehouseResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\PickingListsWarehousesBackendApi\Processor\Reader\PickingListWarehouseResourceRelationshipReaderInterface
     */
    protected PickingListWarehouseResourceRelationshipReaderInterface $pickingListWarehouseResourceRelationshipReader;

    /**
     * @var \Spryker\Glue\PickingListsWarehousesBackendApi\Processor\Filter\PickingListResourceFilterInterface
     */
    protected PickingListResourceFilterInterface $pickingListResourceFilter;

    /**
     * @param \Spryker\Glue\PickingListsWarehousesBackendApi\Processor\Reader\PickingListWarehouseResourceRelationshipReaderInterface $pickingListWarehouseResourceRelationshipReader
     * @param \Spryker\Glue\PickingListsWarehousesBackendApi\Processor\Filter\PickingListResourceFilterInterface $pickingListResourceFilter
     */
    public function __construct(
        PickingListWarehouseResourceRelationshipReaderInterface $pickingListWarehouseResourceRelationshipReader,
        PickingListResourceFilterInterface $pickingListResourceFilter
    ) {
        $this->pickingListWarehouseResourceRelationshipReader = $pickingListWarehouseResourceRelationshipReader;
        $this->pickingListResourceFilter = $pickingListResourceFilter;
    }

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return void
     */
    public function addPickingListWarehouseRelationships(
        array $glueResourceTransfers,
        GlueRequestTransfer $glueRequestTransfer
    ): void {
        $pickingListsResourceTransfers = $this->pickingListResourceFilter->filterPickingListResources($glueResourceTransfers);
        $pickingListUuids = $this->extractPickingListUuids($pickingListsResourceTransfers);

        $warehouseRelationshipTransfersIndexedByPickingListUuids = $this->pickingListWarehouseResourceRelationshipReader
            ->getWarehouseRelationshipsIndexedByPickingListUuid($pickingListUuids);

        $this->addWarehouseRelationshipsToGlueResourceTransfers(
            $pickingListsResourceTransfers,
            $warehouseRelationshipTransfersIndexedByPickingListUuids,
        );
    }

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     *
     * @return list<string>
     */
    protected function extractPickingListUuids(array $glueResourceTransfers): array
    {
        $pickingListUuids = [];
        foreach ($glueResourceTransfers as $glueResourceTransfer) {
            $pickingListUuids[] = $glueResourceTransfer->getIdOrFail();
        }

        return $pickingListUuids;
    }

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     * @param array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer> $warehouseRelationshipTransfersIndexedByPickingListUuids
     *
     * @return void
     */
    protected function addWarehouseRelationshipsToGlueResourceTransfers(
        array $glueResourceTransfers,
        array $warehouseRelationshipTransfersIndexedByPickingListUuids
    ): void {
        foreach ($glueResourceTransfers as $glueResourceTransfer) {
            $warehouseRelationshipTransfer = $warehouseRelationshipTransfersIndexedByPickingListUuids[$glueResourceTransfer->getIdOrFail()] ?? null;

            if (!$warehouseRelationshipTransfer) {
                continue;
            }

            $glueResourceTransfer->addRelationship($warehouseRelationshipTransfer);
        }
    }
}
