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
     * - Populates QuickOrderItemTransfer ProductConcrete property by provided SKU property.
     * - Skips QuickOrderItemTransfers with empty SKU property.
     * - Sets empty ProductConcrete proeprty with error message if product was not found by SKU property.
     * - Validates QuickOrderItemTransfer using pre-configured `ItemValidatorPluginInterface` plugins.
     * - Sets validation error messages into QuickOrderItemTransfer messages property.
     * - Adjusts QuickOrderItemTransfer fields based on validation suggested values.
     * - Expands ProductConcrete with additional data using pre-configured `ProductConcreteExpanderPluginInterface` plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    public function buildQuickOrderTransfer(QuickOrderTransfer $quickOrderTransfer): QuickOrderTransfer;
}
