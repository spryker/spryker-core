<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Business;

interface ProductLabelGuiFacadeInterface
{
    /**
     * Specification:
     * - Updates the position field of the given list of product-label transfers and persists the changes
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductLabelTransfer[] $productLabelTransferCollection
     * @param int[] $positionMap
     *
     * @return void
     */
    public function updateLabelPositions(array $productLabelTransferCollection, array $positionMap);
}
