<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage;

use Generated\Shared\Transfer\ProductConcreteTransfer;
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
     * - Expands ProductConcreteTransfer with quantity restrictions data if exists.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function expandProductConcreteTransferWithQuantityRestrictions(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer;
}
