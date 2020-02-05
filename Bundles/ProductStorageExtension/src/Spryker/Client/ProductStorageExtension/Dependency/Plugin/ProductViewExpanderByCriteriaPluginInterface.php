<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

interface ProductViewExpanderByCriteriaPluginInterface extends ProductViewExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands and returns the provided ProductView transfer objects.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array $productData
     * @param string $localeName
     * @param \Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer|null $productOfferStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransfer(ProductViewTransfer $productViewTransfer, array $productData, $localeName, ?ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer = null): ProductViewTransfer;
}
