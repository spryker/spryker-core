<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Dependency\Plugin;

use Generated\Shared\Transfer\ProductAbstractTransfer;

interface ProductAbstractPluginReadInterface
{
    /**
     * Specification:
     * - Executed after a persisted abstract product is read from database.
     * - Can be used for extending the ProductAbstractTransfer with some extra information or execute any other logic.
     * - To inject instances of the plugin @see \Spryker\Zed\Product\ProductDependencyProvider.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function read(ProductAbstractTransfer $productAbstractTransfer);
}
