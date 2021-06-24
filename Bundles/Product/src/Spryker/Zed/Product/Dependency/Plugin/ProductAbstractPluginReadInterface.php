<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Dependency\Plugin;

use Generated\Shared\Transfer\ProductAbstractTransfer;

/**
 * @deprecated Use {@link \Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractExpanderPluginInterface} instead.
 */
interface ProductAbstractPluginReadInterface
{
    /**
     * Specification:
     * - Executed on retrieved persisted abstract product data.
     * - Can be used to extend the ProductAbstractTransfer with extra information.
     *
     * @api
     *
     * @see \Spryker\Zed\Product\ProductDependencyProvider
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function read(ProductAbstractTransfer $productAbstractTransfer);
}
