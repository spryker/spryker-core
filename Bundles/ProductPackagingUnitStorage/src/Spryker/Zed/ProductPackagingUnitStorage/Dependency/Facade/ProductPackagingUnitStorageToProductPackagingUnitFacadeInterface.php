<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Dependency\Facade;

interface ProductPackagingUnitStorageToProductPackagingUnitFacadeInterface
{
    /**
     * @param array $productPackagingUnitTypeIds
     *
     * @return array
     */
    public function findProductIdsByProductPackagingUnitTypeIds(array $productPackagingUnitTypeIds): array;
}
