<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsBackendApi\Processor\Reader;

use Generated\Shared\Transfer\SalesShipmentCriteriaTransfer;
use Generated\Shared\Transfer\SalesShipmentResourceCollectionTransfer;

interface SalesShipmentReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesShipmentCriteriaTransfer $salesShipmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesShipmentResourceCollectionTransfer
     */
    public function getSalesShipmentResourceCollection(SalesShipmentCriteriaTransfer $salesShipmentCriteriaTransfer): SalesShipmentResourceCollectionTransfer;
}
