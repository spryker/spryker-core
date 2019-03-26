<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductBundlePostSavePluginInterface
{
    /**
     * Specification:
     * - This plugin is executed after saving products in product bundle.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    public function execute(ProductConcreteTransfer $productConcreteTransfer): void;
}
