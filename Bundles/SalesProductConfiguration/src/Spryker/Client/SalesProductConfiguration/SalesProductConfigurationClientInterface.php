<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesProductConfiguration;

use Generated\Shared\Transfer\OrderTransfer;

interface SalesProductConfigurationClientInterface
{
    /**
     * Specification:
     * - Requires `ItemTransfer.groupKey` and `ItemTransfer.salesOrderItemConfiguration` transfer properties to be set.
     * - Requires `OrderTransfer.items.groupKey` transfer properties to be set.
     * - Expands items with product configuration based on data from order items.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function expandItemsWithProductConfiguration(array $itemTransfers, OrderTransfer $orderTransfer): array;
}
