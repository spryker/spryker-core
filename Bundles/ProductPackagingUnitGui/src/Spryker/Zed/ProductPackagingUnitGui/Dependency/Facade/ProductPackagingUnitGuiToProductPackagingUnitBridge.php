<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade;

use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;

class ProductPackagingUnitGuiToProductPackagingUnitBridge implements ProductPackagingUnitGuiToProductPackagingUnitInterface
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
     * {@inheritdoc}
     *
     * @return string[]
     */
    public function getInfrastructuralPackagingUnitTypeKeys(): array
    {
        return $this->productPackagingUnitFacade->getInfrastructuralPackagingUnitTypeKeys();
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    public function getProductPackagingUnitTypeById(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ProductPackagingUnitTypeTransfer {
        return $this->productPackagingUnitFacade->getProductPackagingUnitTypeById($productPackagingUnitTypeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return int
     */
    public function getCountProductPackagingUnitsForType(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): int {
        return $this->productPackagingUnitFacade->getCountProductPackagingUnitsForType($productPackagingUnitTypeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    public function createProductPackagingUnitType(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ProductPackagingUnitTypeTransfer {
        return $this->productPackagingUnitFacade->createProductPackagingUnitType($productPackagingUnitTypeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    public function updateProductPackagingUnitType(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ProductPackagingUnitTypeTransfer {
        return $this->productPackagingUnitFacade->updateProductPackagingUnitType($productPackagingUnitTypeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return bool
     */
    public function deleteProductPackagingUnitType(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): bool {
        return $this->productPackagingUnitFacade->deleteProductPackagingUnitType($productPackagingUnitTypeTransfer);
    }
}
