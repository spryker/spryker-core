<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business\Model;

interface ProductSetOrganizerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer[] $productSetTransfers
     *
     * @return void
     */
    public function reorderProductSets(array $productSetTransfers);
}
