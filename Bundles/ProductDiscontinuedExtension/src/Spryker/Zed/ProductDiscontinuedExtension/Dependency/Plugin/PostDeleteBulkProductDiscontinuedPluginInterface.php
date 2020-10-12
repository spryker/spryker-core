<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer;

interface PostDeleteBulkProductDiscontinuedPluginInterface
{
    /**
     * Specification:
     *  - Executes after selected ProductDiscontinued transfers were deleted.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer
     *
     * @return void
     */
    public function execute(ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer): void;
}
