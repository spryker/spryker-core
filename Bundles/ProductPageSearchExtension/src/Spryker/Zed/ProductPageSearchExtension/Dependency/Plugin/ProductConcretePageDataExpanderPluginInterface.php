<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductConcretePageDataExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands the provided ProductConcretePageSearchTransfer object's data by reference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     *
     * @return void
     */
    public function expand(ProductConcreteTransfer $productConcreteTransfer, ProductConcretePageSearchTransfer $productConcretePageSearchTransfer): void;
}
