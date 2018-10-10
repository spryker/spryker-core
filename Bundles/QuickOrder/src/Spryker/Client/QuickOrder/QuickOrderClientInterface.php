<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductQuantityValidationResponseTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;

interface QuickOrderClientInterface
{
    /**
     * Specification:
     * - Expands ProductConcreteTransfer with additional data using pre-configured plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function expandProductConcrete(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer;

    /**
     * Specification:
     * - Returns CurrentProductPriceTransfer based on the provided product ID, product abstract ID and quantity.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function getCurrentProductPrice(ItemTransfer $itemTransfer): CurrentProductPriceTransfer;

    /**
     * Specification:
     * - Validates given product quantity against its quantity restrictions if present.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductQuantityValidationResponseTransfer
     */
    public function validateQuantityRestrictions(QuickOrderItemTransfer $quickOrderItemTransfer): ProductQuantityValidationResponseTransfer;

    /**
     * Specification:
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer[] $quickOrderItemTransfers
     *
     * @return \Generated\Shared\Transfer\ProductConcreteStorageTransfer[]
     */
    public function findProductConcretesByQuickOrderItemTransfers(array $quickOrderItemTransfers): array;
}
