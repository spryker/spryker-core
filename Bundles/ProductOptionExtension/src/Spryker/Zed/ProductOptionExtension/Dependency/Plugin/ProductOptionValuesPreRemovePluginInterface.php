<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductOptionGroupTransfer;

interface ProductOptionValuesPreRemovePluginInterface
{
    /**
     * Specification:
     * - Plugin is triggered before product option values are removed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return void
     */
    public function preRemove(ProductOptionGroupTransfer $productOptionGroupTransfer): void;
}
