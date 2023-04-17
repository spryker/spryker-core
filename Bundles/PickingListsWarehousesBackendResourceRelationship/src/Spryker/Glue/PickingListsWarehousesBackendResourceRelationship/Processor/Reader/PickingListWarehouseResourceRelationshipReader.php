<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsWarehousesBackendResourceRelationship\Processor\Reader;

use Generated\Shared\Transfer\GlueRelationshipTransfer;
use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListConditionsTransfer;
use Generated\Shared\Transfer\PickingListCriteriaTransfer;
use Generated\Shared\Transfer\StockConditionsTransfer;
use Generated\Shared\Transfer\StockCriteriaTransfer;
use Spryker\Glue\PickingListsWarehousesBackendResourceRelationship\Dependency\Facade\PickingListsWarehousesBackendResourceRelationshipToPickingListFacadeInterface;
use Spryker\Glue\PickingListsWarehousesBackendResourceRelationship\Dependency\Resource\PickingListsWarehousesBackendResourceRelationshipToWarehousesBackendApiResourceInterface;

class PickingListWarehouseResourceRelationshipReader implements PickingListWarehouseResourceRelationshipReaderInterface
{
    /**
     * @var \Spryker\Glue\PickingListsWarehousesBackendResourceRelationship\Dependency\Resource\PickingListsWarehousesBackendResourceRelationshipToWarehousesBackendApiResourceInterface
     */
    protected PickingListsWarehousesBackendResourceRelationshipToWarehousesBackendApiResourceInterface $warehousesBackendApiResource;

    /**
     * @var \Spryker\Glue\PickingListsWarehousesBackendResourceRelationship\Dependency\Facade\PickingListsWarehousesBackendResourceRelationshipToPickingListFacadeInterface
     */
    protected PickingListsWarehousesBackendResourceRelationshipToPickingListFacadeInterface $pickingListFacade;

    /**
     * @param \Spryker\Glue\PickingListsWarehousesBackendResourceRelationship\Dependency\Resource\PickingListsWarehousesBackendResourceRelationshipToWarehousesBackendApiResourceInterface $warehousesBackendApiResource
     * @param \Spryker\Glue\PickingListsWarehousesBackendResourceRelationship\Dependency\Facade\PickingListsWarehousesBackendResourceRelationshipToPickingListFacadeInterface $pickingListFacade
     */
    public function __construct(
        PickingListsWarehousesBackendResourceRelationshipToWarehousesBackendApiResourceInterface $warehousesBackendApiResource,
        PickingListsWarehousesBackendResourceRelationshipToPickingListFacadeInterface $pickingListFacade
    ) {
        $this->warehousesBackendApiResource = $warehousesBackendApiResource;
        $this->pickingListFacade = $pickingListFacade;
    }

    /**
     * @param list<string> $pickingListUuids
     *
     * @return array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer>
     */
    public function getWarehouseRelationshipsIndexedByPickingListUuid(array $pickingListUuids): array
    {
        $indexedWarehouseRelationshipTransfers = [];
        $pickingListCollectionTransfer = $this->getPickingListCollectionByPickingListUuids($pickingListUuids);
        $warehouseUuidsIndexedByPickingListUuids = $this->getWarehouseUuidsIndexedByPickingListUuid($pickingListCollectionTransfer);

        $stockUuids = array_unique(array_values($warehouseUuidsIndexedByPickingListUuids));
        $warehouseResourcesIndexedByStockUuid = $this->getWarehouseResourcesIndexedByStockUuid($stockUuids);

        foreach ($warehouseUuidsIndexedByPickingListUuids as $pickingListUuid => $stockUuid) {
            $warehouseResource = $warehouseResourcesIndexedByStockUuid[$stockUuid] ?? null;

            if (!$warehouseResource) {
                continue;
            }

            $indexedWarehouseRelationshipTransfers[$pickingListUuid] = (new GlueRelationshipTransfer())->addResource($warehouseResource);
        }

        return $indexedWarehouseRelationshipTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return array<string, string>
     */
    protected function getWarehouseUuidsIndexedByPickingListUuid(PickingListCollectionTransfer $pickingListCollectionTransfer): array
    {
        $indexedWarehouseUuids = [];
        foreach ($pickingListCollectionTransfer->getPickingLists() as $pickingListTransfer) {
            $indexedWarehouseUuids[$pickingListTransfer->getUuidOrFail()] = $pickingListTransfer->getWarehouseOrFail()->getUuidOrFail();
        }

        return $indexedWarehouseUuids;
    }

    /**
     * @param list<string> $pickingListUuids
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    protected function getPickingListCollectionByPickingListUuids(array $pickingListUuids): PickingListCollectionTransfer
    {
        $pickingListConditionsTransfer = (new PickingListConditionsTransfer())
            ->setUuids($pickingListUuids);
        $pickingListCriteriaTransfer = (new PickingListCriteriaTransfer())
            ->setPickingListConditions($pickingListConditionsTransfer);

        return $this->pickingListFacade->getPickingListCollection($pickingListCriteriaTransfer);
    }

    /**
     * @param list<string> $stockUuids
     *
     * @return array<string, \Generated\Shared\Transfer\GlueResourceTransfer>
     */
    protected function getWarehouseResourcesIndexedByStockUuid(array $stockUuids): array
    {
        $stockCriteriaTransfer = (new StockCriteriaTransfer())
            ->setStockConditions((new StockConditionsTransfer())->setUuids($stockUuids));

        $warehouseResourceCollectionTransfer = $this->warehousesBackendApiResource->getWarehouseResourceCollection($stockCriteriaTransfer);

        $indexedWarehouseResources = [];
        foreach ($warehouseResourceCollectionTransfer->getWarehouseResources() as $warehouseResource) {
            $indexedWarehouseResources[$warehouseResource->getIdOrFail()] = $warehouseResource;
        }

        return $indexedWarehouseResources;
    }
}
