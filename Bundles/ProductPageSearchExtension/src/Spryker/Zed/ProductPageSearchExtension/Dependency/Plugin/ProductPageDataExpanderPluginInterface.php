<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductPageSearchTransfer;

/**
 * Provides extension capabilities for "ProductPageSearchTransfer" expanding.
 */
interface ProductPageDataExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands the provided ProductPageSearch transfer object's data by reference.
     *
     * @api
     *
     * @param array<string, mixed> $productData
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productAbstractPageSearchTransfer
     *
     * @return void
     */
    public function expandProductPageData(array $productData, ProductPageSearchTransfer $productAbstractPageSearchTransfer);
}
