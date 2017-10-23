<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Dependency\Plugin;

use Generated\Shared\Transfer\ProductAbstractTransfer;

interface ProductAbstractPluginCreateInterface
{
    /**
     * Specification:
     * - Executed before and after an abstract product is created.
     * - Can be used for persisting other abstract product related information to database or execute any other logic.
     * - The ID of the abstract product is available only if the plugin executed on "after" event.
     * - To inject instances of the plugin @see \Spryker\Zed\Product\ProductDependencyProvider.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function create(ProductAbstractTransfer $productAbstractTransfer);
}
