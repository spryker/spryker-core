<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitsRestApi\Dependency\Facade;

use Generated\Shared\Transfer\ProductPackagingUnitTransfer;

class ProductMeasurementUnitsRestApiToProductPackagingUnitFacadeBridge implements ProductMeasurementUnitsRestApiToProductPackagingUnitFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitFacadeInterface
     */
    protected $productPackagingUnitFacade;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitFacadeInterface $productPackagingUnitFacade
     */
    public function __construct($productPackagingUnitFacade)
    {
        $this->productPackagingUnitFacade = $productPackagingUnitFacade;
    }

    /**
     * @param string $productSku
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTransfer|null
     */
    public function findProductPackagingUnitByProductSku(string $productSku): ?ProductPackagingUnitTransfer
    {
        return $this->productPackagingUnitFacade->findProductPackagingUnitByProductSku($productSku);
    }
}
