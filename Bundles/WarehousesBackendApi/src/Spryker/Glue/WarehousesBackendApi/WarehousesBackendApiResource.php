<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehousesBackendApi;

use Generated\Shared\Transfer\StockCriteriaTransfer;
use Generated\Shared\Transfer\WarehouseResourceCollectionTransfer;
use Spryker\Glue\Kernel\Backend\AbstractRestResource;

/**
 * @method \Spryker\Glue\WarehousesBackendApi\WarehousesBackendApiFactory getFactory()
 */
class WarehousesBackendApiResource extends AbstractRestResource implements WarehousesBackendApiResourceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StockCriteriaTransfer $stockCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseResourceCollectionTransfer
     */
    public function getWarehouseResourceCollection(StockCriteriaTransfer $stockCriteriaTransfer): WarehouseResourceCollectionTransfer
    {
        return $this->getFactory()
            ->createWarehouseResourceReader()
            ->getWarehouseResourceCollection($stockCriteriaTransfer);
    }
}
