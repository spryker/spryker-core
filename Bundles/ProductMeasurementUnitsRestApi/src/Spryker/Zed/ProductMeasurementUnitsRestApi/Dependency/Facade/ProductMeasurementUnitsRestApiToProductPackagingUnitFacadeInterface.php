<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitsRestApi\Dependency\Facade;

use Generated\Shared\Transfer\ProductPackagingUnitTransfer;

interface ProductMeasurementUnitsRestApiToProductPackagingUnitFacadeInterface
{
    /**
     * @param string $productSku
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTransfer|null
     */
    public function findProductPackagingUnitByProductSku(string $productSku): ?ProductPackagingUnitTransfer;
}
