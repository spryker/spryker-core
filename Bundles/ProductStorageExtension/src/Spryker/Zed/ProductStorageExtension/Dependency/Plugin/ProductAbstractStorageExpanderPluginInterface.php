<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductAbstractStorageTransfer;

/**
 * Provides ability to expand ProductAbstractStorage transfer object.
 */
interface ProductAbstractStorageExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands and returns the provided ProductAbstractStorage transfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractStorageTransfer $productAbstractStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractStorageTransfer
     */
    public function expand(ProductAbstractStorageTransfer $productAbstractStorageTransfer): ProductAbstractStorageTransfer;
}
