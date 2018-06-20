<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedGui\Dependency\Facade;

use Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer;
use Generated\Shared\Transfer\ProductDiscontinueRequestTransfer;

interface ProductDiscontinuedGuiToProductDiscontinuedFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinueRequestTransfer $productDiscontinuedRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    public function unmarkProductAsDiscontinued(
        ProductDiscontinueRequestTransfer $productDiscontinuedRequestTransfer
    ): ProductDiscontinuedResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinueRequestTransfer $productDiscontinuedRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    public function markProductAsDiscontinued(
        ProductDiscontinueRequestTransfer $productDiscontinuedRequestTransfer
    ): ProductDiscontinuedResponseTransfer;

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    public function findProductDiscontinuedByProductId(int $idProduct): ProductDiscontinuedResponseTransfer;
}
