<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Dependency\Facade;

use Generated\Shared\Transfer\ProductPackagingLeadProductTransfer;

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
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductPackagingLeadProductTransfer|null
     */
    public function getProductPackagingLeadProductByIdProductAbstract(
        int $idProductAbstract
    ): ?ProductPackagingLeadProductTransfer {
        return $this->productPackagingUnitFacade->findProductPackagingLeadProductByIdProductAbstract($idProductAbstract);
    }

    /**
     * @return string
     */
    public function getDefaultPackagingUnitTypeName(): string
    {
        return $this->productPackagingUnitFacade->getDefaultPackagingUnitTypeName();
    }

    /**
     * @param array $productPackagingUnitTypeIds
     *
     * @return array
     */
    public function getIdProductAbstractsByIdProductPackagingUnitTypes(array $productPackagingUnitTypeIds): array
    {
        return $this->productPackagingUnitFacade->getIdProductAbstractsByIdProductPackagingUnitTypes($productPackagingUnitTypeIds);
    }
}
