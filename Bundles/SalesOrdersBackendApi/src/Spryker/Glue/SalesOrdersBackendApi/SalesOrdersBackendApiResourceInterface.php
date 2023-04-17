<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesOrdersBackendApi;

use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderResourceCollectionTransfer;

interface SalesOrdersBackendApiResourceInterface
{
    /**
     * Specification:
     * - Retrieves sales order entities collection filtered by criteria.
     * - Maps found sales order entities to order resources.
     * - Executes {@link \Spryker\Glue\SalesOrdersBackendApiExtension\Dependency\Plugin\ApiOrdersAttributesMapperPluginInterface} plugins stack.
     * - Returns `OrderResourceCollection` filled with found orders.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderResourceCollectionTransfer
     */
    public function getOrderResourceCollection(OrderListTransfer $orderListTransfer): OrderResourceCollectionTransfer;
}
