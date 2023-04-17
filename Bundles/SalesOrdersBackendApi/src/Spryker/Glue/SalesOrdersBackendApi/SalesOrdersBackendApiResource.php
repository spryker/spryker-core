<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesOrdersBackendApi;

use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderResourceCollectionTransfer;
use Spryker\Glue\Kernel\Backend\AbstractRestResource;

/**
 * @method \Spryker\Glue\SalesOrdersBackendApi\SalesOrdersBackendApiFactory getFactory()
 */
class SalesOrdersBackendApiResource extends AbstractRestResource implements SalesOrdersBackendApiResourceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderResourceCollectionTransfer
     */
    public function getOrderResourceCollection(OrderListTransfer $orderListTransfer): OrderResourceCollectionTransfer
    {
        return $this->getFactory()
            ->createSalesOrdersResourceReader()
            ->getOrderResourceCollection($orderListTransfer);
    }
}
