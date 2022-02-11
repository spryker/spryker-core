<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApprovalGui\Communication\Expander;

use Generated\Shared\Transfer\ButtonCollectionTransfer;

interface ProductApprovalProductTableActionExpanderInterface
{
    /**
     * @param array<mixed> $productData
     * @param \Generated\Shared\Transfer\ButtonCollectionTransfer $buttonCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ButtonCollectionTransfer
     */
    public function expandWithProductApprovalStatusActions(
        array $productData,
        ButtonCollectionTransfer $buttonCollectionTransfer
    ): ButtonCollectionTransfer;
}
