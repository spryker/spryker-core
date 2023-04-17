<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsWarehousesBackendResourceRelationship\Dependency\Resource;

use Generated\Shared\Transfer\StockCriteriaTransfer;
use Generated\Shared\Transfer\WarehouseResourceCollectionTransfer;

class PickingListsWarehousesBackendResourceRelationshipToWarehousesBackendApiResourceBridge implements PickingListsWarehousesBackendResourceRelationshipToWarehousesBackendApiResourceInterface
{
    /**
     * @var \Spryker\Glue\WarehousesBackendApi\WarehousesBackendApiResourceInterface
     */
    protected $warehousesBackendApiResource;

    /**
     * @param \Spryker\Glue\WarehousesBackendApi\WarehousesBackendApiResourceInterface $warehousesBackendApiResource
     */
    public function __construct($warehousesBackendApiResource)
    {
        $this->warehousesBackendApiResource = $warehousesBackendApiResource;
    }

    /**
     * @param \Generated\Shared\Transfer\StockCriteriaTransfer $stockCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseResourceCollectionTransfer
     */
    public function getWarehouseResourceCollection(StockCriteriaTransfer $stockCriteriaTransfer): WarehouseResourceCollectionTransfer
    {
        return $this->warehousesBackendApiResource->getWarehouseResourceCollection($stockCriteriaTransfer);
    }
}
