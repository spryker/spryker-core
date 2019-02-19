<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductDiscontinuedStorage;

use Generated\Shared\Transfer\ItemValidationTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

/**
 * @method \Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedStorageFactory getFactory()
 */
interface ProductDiscontinuedStorageClientInterface
{
    /**
     * Specification:
     * - Finds a product discontinued within Storage with a given concrete product sku for given locale.
     * - Returns null if product discontinued was not found.
     *
     * @api
     *
     * @param string $concreteSku
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedStorageTransfer|null
     */
    public function findProductDiscontinuedStorage(string $concreteSku, string $locale): ?ProductDiscontinuedStorageTransfer;

    /**
     * Specification:
     * - Finds a product discontinued within Storage with a given concrete product sku for given locale.
     * - Returns true if product discontinued was found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $locale
     *
     * @return bool
     */
    public function isProductDiscontinued(ProductViewTransfer $productViewTransfer, string $locale): bool;

    /**
     * Specification:
     *  - Adds discontinued mark to discontinued super attributes of abstract product.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandDiscontinuedProductSuperAttributes(ProductViewTransfer $productViewTransfer, string $localeName): ProductViewTransfer;

    /**
     * Specification:
     *  - Marks product as not available if product is discontinued.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandDiscontinuedProductAvailability(ProductViewTransfer $productViewTransfer, string $localeName): ProductViewTransfer;

    /**
     * Specification:
     * - Requires ItemTransfer inside ItemValidationTransfer.
     * - Returns not modified ItemValidationTransfer if ItemTransfer.id is missing.
     * - Requires sku inside ItemTransfer.
     * - Calls ProductDiscontinuedStorageClient::findProductDiscontinuedStorage() to know if product is discontinued or not (uses current locale).
     * - Adds error message if product is discontinued. Otherwise returns not modified ItemValidationTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemValidationTransfer $itemValidationTransfer
     *
     * @return \Generated\Shared\Transfer\ItemValidationTransfer
     */
    public function validateItemProductDiscontinued(ItemValidationTransfer $itemValidationTransfer): ItemValidationTransfer;
}
