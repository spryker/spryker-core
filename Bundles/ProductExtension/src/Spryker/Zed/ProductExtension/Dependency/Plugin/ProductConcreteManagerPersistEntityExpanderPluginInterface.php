<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductConcreteManagerPersistEntityExpanderPluginInterface
{
    /**
     * Specification:
     * - Persists data related to the product concrete
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcrete
     *
     * @return void
     */
    public function persistRelatedData(ProductConcreteTransfer $productConcrete): void;
}
