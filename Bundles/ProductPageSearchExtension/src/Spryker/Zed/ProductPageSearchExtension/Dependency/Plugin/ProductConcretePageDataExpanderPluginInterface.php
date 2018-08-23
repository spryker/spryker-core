<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductConcretePageSearchTransfer;

interface ProductConcretePageDataExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands the provided ProductConcretePageSearchTransfer object's data by reference.
     *
     * @api
     *
     * @param array $productConcreteData
     * @param \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer
     *
     * @return void
     */
    public function expand(array $productConcreteData, ProductConcretePageSearchTransfer $productConcretePageSearchTransfer): void;
}
