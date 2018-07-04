<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\ProductPackagingUnit;

use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use Generated\Shared\Transfer\ProductPackagingLeadProductTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTransfer;

interface ProductPackagingUnitReaderInterface
{
    /**
     * @param int $idProductPackagingUnit
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTransfer|null
     */
    public function getProductPackagingUnitById(
        int $idProductPackagingUnit
    ): ?ProductPackagingUnitTransfer;

    /**
     * @param string $productPackagingUnitSku
     *
     * @return \Generated\Shared\Transfer\ProductPackagingLeadProductTransfer|null
     */
    public function findProductPackagingLeadProductByProductPackagingSku(string $productPackagingUnitSku): ?ProductPackagingLeadProductTransfer;

    /**
     * @param int $idProductMeasurementSalesUnit
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    public function findProductMeasurementSalesUnitTransfer(int $idProductMeasurementSalesUnit): ProductMeasurementSalesUnitTransfer;

    /**
     * @param array $skus
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTransfer[]
     */
    public function findProductPackagingUnitBySkus(array $skus): array;
}
