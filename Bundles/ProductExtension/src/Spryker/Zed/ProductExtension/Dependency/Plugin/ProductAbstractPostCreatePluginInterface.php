<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductAbstractTransfer;

interface ProductAbstractPostCreatePluginInterface
{
    /**
     * Specification:
     * - Executed on "after" event when an abstract product is created.
     * - Can be used to persist additional abstract product related information.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function create(ProductAbstractTransfer $productAbstractTransfer): ProductAbstractTransfer;
}
