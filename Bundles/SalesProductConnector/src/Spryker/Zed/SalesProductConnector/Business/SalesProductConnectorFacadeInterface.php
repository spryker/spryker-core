<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

interface SalesProductConnectorFacadeInterface
{
    /**
     * Specification:
     * - Saves product metadata information (image, super attributes) into a sales table to hydrate them later
     *
     * @api
     *
     * @deprecated Use {@link saveOrderItemMetadata()} instead
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveItemMetadata(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse);

    /**
     * Specification:
     * - Saves product metadata information (image, super attributes) into a sales table to hydrate them later
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderItemMetadata(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer);

    /**
     * Specification:
     * - Hydrates product meta information (image, super attributes) into an order transfer
     *
     * @api
     *
     * @deprecated Use {@link expandOrderItemsWithMetadata()} instead.
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateItemMetadata(OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Hydrates product ids (abstract / concrete) into an order based on their sku
     *
     * @api
     *
     * @deprecated Use {@link SalesProductConnectorFacade::expandOrderItemsWithProductIds()} instead.
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function hydrateProductIds(OrderTransfer $orderTransfer);

    /**
     * Specification:
     * - Expands order items with metadata information.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItemsWithMetadata(array $itemTransfers): array;

    /**
     * Specification:
     * - Expands OrderTransfers with metadata information.
     * - Requires OrderTransfer::idSalesOrder to be set.
     * - Requires ItemTransfer::fkSalesOrder at OrderTransfer::items to be set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer[] $orderTransfers
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    public function expandOrdersWithMetadata(array $orderTransfers): array;

    /**
     * Specification:
     * - Hydrates product ids (abstract / concrete) into an order items based on their skus.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItemsWithProductIds(array $itemTransfers): array;
}
