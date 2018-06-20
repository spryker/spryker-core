<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit;

use Generated\Shared\Transfer\ProductPackagingUnitTransfer;

interface ProductPackagingUnitReaderInterface
{
    /**
     * @param int $productPackagingUnitId
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTransfer|null
     */
    public function getProductPackagingUnitById(
        int $productPackagingUnitId
    ): ?ProductPackagingUnitTransfer;
}
