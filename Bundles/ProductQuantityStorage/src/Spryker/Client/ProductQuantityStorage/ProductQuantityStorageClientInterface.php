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
     * - Requires ItemTransfer inside ItemValidationTransfer.
     * - Returns not modified ItemValidationTransfer if ItemTransfer.id is missing.
     * - Calls ProductQuantityStorageClient::findProductQuantityStorage() to find product quantity restrictions.
     * - Returns not modified ItemValidationTransfer if product quantity restrictions was not found.
     * - Requires quantity inside ItemTransfer and checks it with the product quantity restrictions.
     * - Returns ItemValidationTransfer with messages and suggestedValues.quantity in case if ItemTransfer.quantity falls in restrictions.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemValidationTransfer $itemValidationTransfer
     *
     * @return \Generated\Shared\Transfer\ItemValidationTransfer
     */
    public function validateItemProductQuantity(ItemValidationTransfer $itemValidationTransfer): ItemValidationTransfer;
}
