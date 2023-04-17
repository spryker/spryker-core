<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsShipmentsBackendResourceRelationship\Dependency\Resource;

use Generated\Shared\Transfer\SalesShipmentCriteriaTransfer;
use Generated\Shared\Transfer\SalesShipmentResourceCollectionTransfer;

class PickingListsShipmentsBackendResourceRelationshipToShipmentsBackendApiResourceBridge implements PickingListsShipmentsBackendResourceRelationshipToShipmentsBackendApiResourceInterface
{
    /**
     * @var \Spryker\Glue\ShipmentsBackendApi\ShipmentsBackendApiResourceInterface
     */
    protected $shipmentsBackendApiResource;

    /**
     * @param \Spryker\Glue\ShipmentsBackendApi\ShipmentsBackendApiResourceInterface $shipmentsBackendApiResource
     */
    public function __construct($shipmentsBackendApiResource)
    {
        $this->shipmentsBackendApiResource = $shipmentsBackendApiResource;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesShipmentCriteriaTransfer $salesShipmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesShipmentResourceCollectionTransfer
     */
    public function getSalesShipmentResourceCollection(SalesShipmentCriteriaTransfer $salesShipmentCriteriaTransfer): SalesShipmentResourceCollectionTransfer
    {
        return $this->shipmentsBackendApiResource->getSalesShipmentResourceCollection($salesShipmentCriteriaTransfer);
    }
}
