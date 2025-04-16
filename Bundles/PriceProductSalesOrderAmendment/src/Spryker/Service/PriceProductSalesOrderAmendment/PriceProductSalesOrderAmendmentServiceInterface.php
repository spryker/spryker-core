<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductSalesOrderAmendment;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface PriceProductSalesOrderAmendmentServiceInterface
{
    /**
     * Specification:
     * - Requires `ItemTransfer.sku` to be set.
     * - Builds a group key for the original sales order item price.
     * - Uses `ItemTransfer.sku` as a default group key.
     * - Executes {@link \Spryker\Service\PriceProductSalesOrderAmendmentExtension\Dependency\Plugin\OriginalSalesOrderItemPriceGroupKeyExpanderPluginInterface} plugins to expand the group key.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    public function buildOriginalSalesOrderItemPriceGroupKey(ItemTransfer $itemTransfer): string;

    /**
     * Specification:
     * - Decides whether to use the original sales order item price based on the provided `salesOrderItemUnitPrice` and `originalSalesOrderItemUnitPrice`.
     * - Based on {@link \Spryker\Service\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentConfig::useBestPriceBetweenOriginalAndSalesOrderItemPrice()} to determine if best price should be used.
     * - If the config method returns `true` replaces price in case the original price is lower then the original price.
     * - Configuration applies for all items. It is not possible to set it for each item separately.
     *
     * @api
     *
     * @param int $salesOrderItemUnitPrice
     * @param int $originalSalesOrderItemUnitPrice
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     *
     * @return int
     */
    public function resolveOriginalSalesOrderItemPrice(
        int $salesOrderItemUnitPrice,
        int $originalSalesOrderItemUnitPrice,
        ?QuoteTransfer $quoteTransfer = null
    ): int;
}
