<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesOrdersBackendApiExtension\Dependency\Plugin;

/**
 * Implement this plugin if you want to map extra data from `OrderTransfer` to `ApiOrdersAttributesTransfer`.
 */
interface ApiOrdersAttributesMapperPluginInterface
{
    /**
     * Specification:
     * - Maps order data from `OrderTransfer` to `ApiOrdersAttributesTransfer`.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     * @param list<\Generated\Shared\Transfer\ApiOrdersAttributesTransfer> $apiOrdersAttributesTransfers
     *
     * @return list<\Generated\Shared\Transfer\ApiOrdersAttributesTransfer>
     */
    public function mapOrderTransfersToApiOrdersAttributesTransfer(
        array $orderTransfers,
        array $apiOrdersAttributesTransfers
    ): array;
}
