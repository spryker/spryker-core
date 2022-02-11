<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductAbstractTransfer;

/**
 * Implement this plugin interface to add logic before an abstract product is created.
 */
interface ProductAbstractPreCreatePluginInterface
{
    /**
     * Specification:
     * - Executed before an abstract product is created.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function preCreate(ProductAbstractTransfer $productAbstractTransfer): ProductAbstractTransfer;
}
