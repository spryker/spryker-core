<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder;

use Generated\Shared\Transfer\QuickOrderTransfer;

interface QuickOrderClientInterface
{
    /**
     * Specification:
     * - Returns the list of ProductConcreteTransfers from QuickOrderTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function getProductsByQuickOrder(QuickOrderTransfer $quickOrderTransfer): array;

    /**
     * Specification:
     * - Expands array of ProductConcreteTransfers with additional data using pre-configured plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function expandProductConcreteTransfers(array $productConcreteTransfers): array;

    /**
     * Specification:
     * - Expects QuickOrderItem.sku for finding product, otherwise proceed to next QuickOrderItem.
     * - Tries to find product based on the sku using ProductConcreteResolver::findProductConcreteBySku().
     * - If product was not found, creates new ProductConcreteTransfer with QuickOrderItem.sku and adds error message.
     * - Puts ProductConcreteTransfer inside QuickOrderItemTransfer.
     * - Validates QuickOrderItemTransfer using pre-configured plugins.
     * - Skips validation if there are no plugins are registered.
     * - Copies ItemValidationTransfer.messages into QuickOrderItemTransfer.messages, if messages exist.
     * - Adjusts QuickOrderItemTransfer fields based on ItemValidationTransfer.suggestedValues, if they exist.
     * - Expands QuickOrderItemTransfer.ProductConcrete with additional data using pre-configured plugins, if ProductConcrete.IdProductConcrete exists.
     * - Skips expanding if there are no plugins are registered.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    public function buildQuickOrderTransfer(QuickOrderTransfer $quickOrderTransfer): QuickOrderTransfer;
}
