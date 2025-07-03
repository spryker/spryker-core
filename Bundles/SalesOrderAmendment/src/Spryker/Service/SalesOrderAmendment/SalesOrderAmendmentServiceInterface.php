<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\SalesOrderAmendment;

use Generated\Shared\Transfer\ItemTransfer;

interface SalesOrderAmendmentServiceInterface
{
    /**
     * Specification:
     * - Requires `ItemTransfer.sku` to be set.
     * - Builds a group key for the original sales order item.
     * - Uses `ItemTransfer.sku` as a default group key.
     * - Executes {@link \Spryker\Service\SalesOrderAmendmentExtension\Dependency\Plugin\OriginalSalesOrderItemGroupKeyExpanderPluginInterface} plugins to expand the group key.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    public function buildOriginalSalesOrderItemGroupKey(ItemTransfer $itemTransfer): string;
}
