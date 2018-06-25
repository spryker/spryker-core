<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductAlternativeStorage;

use Generated\Shared\Transfer\ProductAlternativeStorageTransfer;
use Generated\Shared\Transfer\ProductReplacementStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

/**
 * @method \Spryker\Client\ProductAlternativeStorage\ProductAlternativeStorageFactory getFactory()
 */
interface ProductAlternativeStorageClientInterface
{
    /**
     * Specification:
     * - Finds a product alternative within Storage with a given concrete product sku.
     * - Returns null if product alternative was not found.
     *
     * @api
     *
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeStorageTransfer|null
     */
    public function findProductAlternativeStorage(string $concreteSku): ?ProductAlternativeStorageTransfer;

    /**
     * Specification:
     * - Finds a product replacement for within Storage with a given concrete or abstract product sku.
     * - Returns null if product replacement for was not found.
     *
     * @api
     *
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductReplacementStorageTransfer|null
     */
    public function findProductReplacementForStorage(string $sku): ?ProductReplacementStorageTransfer;

    /**
     * Specification:
     * - Checks if alternative products should be shown for product.
     * - Call stack of plugins AlternativeProductApplicableCheckPluginInterface.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    public function isAlternativeProductApplicable(ProductViewTransfer $productViewTransfer): bool;
}
