<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductBundleConnector\Dependency\Facade;

use Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer;
use Generated\Shared\Transfer\ProductDiscontinueRequestTransfer;

interface ProductDiscontinuedProductBundleConnectorToProductDiscontinuedFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinueRequestTransfer $productDiscontinueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    public function markProductAsDiscontinued(ProductDiscontinueRequestTransfer $productDiscontinueRequestTransfer): ProductDiscontinuedResponseTransfer;

    /**
     * @param int[] $productConcreteIds
     *
     * @return bool
     */
    public function isAnyProductConcreteDiscontinued(array $productConcreteIds): bool;
}
