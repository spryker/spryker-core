<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Dependency\Facade;

use Generated\Shared\Transfer\ProductPackagingLeadProductTransfer;

interface ProductPackagingUnitStorageToProductPackagingUnitFacadeInterface
{
    /**
     * @param int $productAbstractId
     *
     * @return \Generated\Shared\Transfer\ProductPackagingLeadProductTransfer|null
     */
    public function getProductPackagingLeadProductByAbstractId(
        int $productAbstractId
    ): ?ProductPackagingLeadProductTransfer;

    /**
     * @return string
     */
    public function getDefaultPackagingUnitTypeName(): string;
}
