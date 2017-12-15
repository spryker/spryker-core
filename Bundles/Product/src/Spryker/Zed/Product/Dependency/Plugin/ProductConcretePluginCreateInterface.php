<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Dependency\Plugin;

use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductConcretePluginCreateInterface
{
    /**
     * Specification:
     * - Executed on "before" and/or on "after" event when a concrete product is created.
     * - Can be used to persist additional concrete product related information.
     * - The ID of the concrete product is available only if the plugin is executed on "after" event.
     *
     * @api
     *
     * @see \Spryker\Zed\Product\ProductDependencyProvider
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function create(ProductConcreteTransfer $productConcreteTransfer);
}
