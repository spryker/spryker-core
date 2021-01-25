<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Dependency\Facade;

class ProductPackagingUnitStorageToProductPackagingUnitFacadeBridge implements ProductPackagingUnitStorageToProductPackagingUnitFacadeInterface
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
     * @param array $productPackagingUnitTypeIds
     *
     * @return array
     */
    public function findProductIdsByProductPackagingUnitTypeIds(array $productPackagingUnitTypeIds): array
    {
        return $this->productPackagingUnitFacade->findProductIdsByProductPackagingUnitTypeIds($productPackagingUnitTypeIds);
    }
}
