<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\SalesShipmentCollectionTransfer;
use Generated\Shared\Transfer\SalesShipmentResourceCollectionTransfer;

interface SalesShipmentMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesShipmentCollectionTransfer $salesShipmentCollectionTransfer
     * @param \Generated\Shared\Transfer\SalesShipmentResourceCollectionTransfer $salesShipmentResourceCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SalesShipmentResourceCollectionTransfer
     */
    public function mapSalesShipmentCollectionToSalesShipmentResourceCollection(
        SalesShipmentCollectionTransfer $salesShipmentCollectionTransfer,
        SalesShipmentResourceCollectionTransfer $salesShipmentResourceCollectionTransfer
    ): SalesShipmentResourceCollectionTransfer;
}
