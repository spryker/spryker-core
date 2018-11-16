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
     * @deprecated Will be removed without replacement.
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductPackagingLeadProductTransfer|null
     */
    public function getProductPackagingLeadProductByIdProductAbstract(
        int $idProductAbstract
    ): ?ProductPackagingLeadProductTransfer;

    /**
     * @return string
     */
    public function getDefaultProductPackagingUnitTypeName(): string;

    /**
     * @param array $productPackagingUnitTypeIds
     *
     * @return array
     */
    public function findProductAbstractIdsByProductPackagingUnitTypeIds(array $productPackagingUnitTypeIds): array;
}
