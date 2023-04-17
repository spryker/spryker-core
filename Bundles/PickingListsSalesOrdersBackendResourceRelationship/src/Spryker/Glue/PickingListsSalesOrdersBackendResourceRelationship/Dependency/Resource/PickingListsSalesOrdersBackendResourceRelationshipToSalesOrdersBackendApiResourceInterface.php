<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Dependency\Resource;

use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderResourceCollectionTransfer;

interface PickingListsSalesOrdersBackendResourceRelationshipToSalesOrdersBackendApiResourceInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderResourceCollectionTransfer
     */
    public function getOrderResourceCollection(OrderListTransfer $orderListTransfer): OrderResourceCollectionTransfer;
}
