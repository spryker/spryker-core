<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesOrdersBackendApiExtension\Dependency\Plugin;

/**
 * Implement this plugin if you want to map extra data from `OrderTransfer` to `OrdersBackendApiAttributesTransfer`.
 */
interface OrdersBackendApiAttributesMapperPluginInterface
{
    /**
     * Specification:
     * - Maps order data from `OrderTransfer` to `OrdersBackendApiAttributesTransfer`.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     * @param list<\Generated\Shared\Transfer\OrdersBackendApiAttributesTransfer> $ordersBackendApiAttributesTransfers
     *
     * @return list<\Generated\Shared\Transfer\OrdersBackendApiAttributesTransfer>
     */
    public function mapOrderTransfersToOrdersBackendApiAttributesTransfers(
        array $orderTransfers,
        array $ordersBackendApiAttributesTransfers
    ): array;
}
