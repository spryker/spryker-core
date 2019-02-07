<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage;

use Generated\Shared\Transfer\ItemValidationTransfer;
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
     * - Checks if product concrete provided in ItemValidationTransfer has product quantity restrictions or not.
     * - Adds recommendedValues with valid ItemValidationTransfer->quantity inside into ItemValidationTransfer and warning message
     *   when product has quantity restrictions.
     * - Returns empty ItemValidationTransfer if product has not quantity restrictions.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemValidationTransfer $itemValidationTransfer
     *
     * @return \Generated\Shared\Transfer\ItemValidationTransfer
     */
    public function validateItemQuantity(ItemValidationTransfer $itemValidationTransfer): ItemValidationTransfer;
}
