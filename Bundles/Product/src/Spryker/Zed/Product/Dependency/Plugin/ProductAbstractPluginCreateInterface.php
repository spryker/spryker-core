<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Dependency\Plugin;

use Generated\Shared\Transfer\ProductAbstractTransfer;

/**
 * @deprecated Use {@link \Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractPostCreatePluginInterface}
 * or {@link \Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractPreCreatePluginInterface} instead.
 */
interface ProductAbstractPluginCreateInterface
{
    /**
     * Specification:
     * - Executed on "before" and/or on "after" event when an abstract product is created.
     * - The ID of the abstract product is available only if the plugin is executed on "after" event.
     * - Can be used to persist additional abstract product related information.
     *
     * @api
     *
     * @see \Spryker\Zed\Product\ProductDependencyProvider
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function create(ProductAbstractTransfer $productAbstractTransfer);
}
