<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsBackendApi;

use Generated\Shared\Transfer\SalesShipmentCriteriaTransfer;
use Generated\Shared\Transfer\SalesShipmentResourceCollectionTransfer;
use Spryker\Glue\Kernel\Backend\AbstractRestResource;

/**
 * @method \Spryker\Glue\ShipmentsBackendApi\ShipmentsBackendApiFactory getFactory()
 */
class ShipmentsBackendApiResource extends AbstractRestResource implements ShipmentsBackendApiResourceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesShipmentCriteriaTransfer $salesShipmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesShipmentResourceCollectionTransfer
     */
    public function getSalesShipmentResourceCollection(SalesShipmentCriteriaTransfer $salesShipmentCriteriaTransfer): SalesShipmentResourceCollectionTransfer
    {
        return $this->getFactory()
            ->createSalesShipmentReader()
            ->getSalesShipmentResourceCollection($salesShipmentCriteriaTransfer);
    }
}
