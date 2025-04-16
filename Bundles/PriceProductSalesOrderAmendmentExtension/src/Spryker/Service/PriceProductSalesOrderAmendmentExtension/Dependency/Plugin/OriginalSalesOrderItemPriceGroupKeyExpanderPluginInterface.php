<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductSalesOrderAmendmentExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ItemTransfer;

/**
 * Implement this plugin interface to expand original sales order item price group key.
 */
interface OriginalSalesOrderItemPriceGroupKeyExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands provided group key.
     *
     * @api
     *
     * @param string $groupKey
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    public function expandGroupKey(string $groupKey, ItemTransfer $itemTransfer): string;
}
