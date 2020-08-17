<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

interface ProductConfigurationStorageClientInterface
{
    /**
     * Specification:
     * - Reads product configuration instance from session first then from storage if not found.
     * - Returns null if configuration instance is not found neither sources.
     *
     * @api
     *
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null
     */
    public function findProductConfigurationInstanceBySku(
        string $sku
    ): ?ProductConfigurationInstanceTransfer;

    /**
     * Specification:
     * - Stores ProductConfigurationInstanceTransfer in the session by SKU.
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     */
    public function storeProductConfigurationInstanceBySku(
        string $sku,
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): void;

    /**
     * Specification:
     * - Expands the product view with the product configuration data.
     * - Expects ProductViewTransfer::sku property to be provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewWithProductConfiguration(ProductViewTransfer $productViewTransfer): productViewTransfer;
}
