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
     * - Expands QuickOrderItemTransfers with validation messages.
     * - Expands ProductConcreteTransfers in QuickOrderItemTransfers with additional data.
     * - Adjusts QuickOrderItemTransfers accordingly to corresponding values.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    public function buildQuickOrderTransfer(QuickOrderTransfer $quickOrderTransfer): QuickOrderTransfer;
}
