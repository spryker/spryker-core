<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedGui\Dependency\Facade;

use Generated\Shared\Transfer\ProductDiscontinuedRequestTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer;

interface ProductDiscontinuedGuiToProductDiscontinuedFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedRequestTransfer $productDiscontinuedRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    public function removeProductDiscontinuedFlag(
        ProductDiscontinuedRequestTransfer $productDiscontinuedRequestTransfer
    ): ProductDiscontinuedResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedRequestTransfer $productDiscontinuedRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    public function discontinueProduct(
        ProductDiscontinuedRequestTransfer $productDiscontinuedRequestTransfer
    ): ProductDiscontinuedResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedRequestTransfer $productDiscontinuedRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    public function findProductDiscontinuedByProductId(
        ProductDiscontinuedRequestTransfer $productDiscontinuedRequestTransfer
    ): ProductDiscontinuedResponseTransfer;
}
