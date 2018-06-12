<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinued;

use Generated\Shared\Transfer\ProductDiscontinuedRequestTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer;

interface ProductDiscontinuedWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedRequestTransfer $productDiscontinuedRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    public function create(ProductDiscontinuedRequestTransfer $productDiscontinuedRequestTransfer): ProductDiscontinuedResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedRequestTransfer $productDiscontinuedRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    public function delete(ProductDiscontinuedRequestTransfer $productDiscontinuedRequestTransfer): ProductDiscontinuedResponseTransfer;
}
