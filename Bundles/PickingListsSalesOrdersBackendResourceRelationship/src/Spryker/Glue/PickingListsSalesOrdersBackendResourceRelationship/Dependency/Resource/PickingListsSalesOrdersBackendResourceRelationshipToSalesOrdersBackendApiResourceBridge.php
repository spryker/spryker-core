<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Dependency\Resource;

use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderResourceCollectionTransfer;

class PickingListsSalesOrdersBackendResourceRelationshipToSalesOrdersBackendApiResourceBridge implements PickingListsSalesOrdersBackendResourceRelationshipToSalesOrdersBackendApiResourceInterface
{
    /**
     * @var \Spryker\Glue\SalesOrdersBackendApi\SalesOrdersBackendApiResourceInterface
     */
    protected $salesOrdersBackendApiResource;

    /**
     * @param \Spryker\Glue\SalesOrdersBackendApi\SalesOrdersBackendApiResourceInterface $salesOrdersBackendApiResource
     */
    public function __construct($salesOrdersBackendApiResource)
    {
        $this->salesOrdersBackendApiResource = $salesOrdersBackendApiResource;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderResourceCollectionTransfer
     */
    public function getOrderResourceCollection(OrderListTransfer $orderListTransfer): OrderResourceCollectionTransfer
    {
        return $this->salesOrdersBackendApiResource->getOrderResourceCollection($orderListTransfer);
    }
}
