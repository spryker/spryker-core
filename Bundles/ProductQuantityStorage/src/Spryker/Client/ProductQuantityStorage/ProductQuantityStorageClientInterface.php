<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ItemValidationResponseTransfer;
use Generated\Shared\Transfer\ProductQuantityStorageTransfer;

interface ProductQuantityStorageClientInterface
{
    /**
     * Specification:
     * - Finds a product quantity within Storage with the given related product ID.
     * - Returns null if product quantity was not found.
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductQuantityStorageTransfer|null
     */
    public function findProductQuantityStorage(int $idProduct): ?ProductQuantityStorageTransfer;

    /**
     * Specification:
     * - Retrieves the nearest valid quantity for a given product based on its product quantity restrictions.
     *
     * @api
     *
     * @param int $idProduct
     * @param int $quantity
     *
     * @return int
     */
    public function getNearestQuantity(int $idProduct, int $quantity): int;

    /**
     * Specification:
     * - Validates ItemTransfer with the product quantity validation.
     * - Returns ItemValidationResponseTransfer with error or warning messages.
     * - In case any fields need to be updated ItemValidationResponseTransfer contains ItemTransfer with 'recommended values transfer' inside.
     * - Returns empty ItemValidationResponseTransfer when no validation errors or warnings.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemValidationResponseTransfer
     */
    public function validateItemTransfer(ItemTransfer $itemTransfer): ItemValidationResponseTransfer;
}
