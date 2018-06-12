<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade;

use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;

interface ProductPackagingUnitGuiToProductPackagingUnitInterface
{
    /**
     * Specification:
     * - Retrieve infrastructural packaging unit type list as an array of strings.
     *
     * @api
     *
     * @return string[]
     */
    public function getInfrastructuralPackagingUnitTypeKeys(): array;

    /**
     * Specification:
     *  - Retrieve a product packaging unit type by ProductPackagingUnitTypeTransfer::idProductPackagingUnitType in the transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    public function getProductPackagingUnitTypeById(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ProductPackagingUnitTypeTransfer;

    /**
     * Specification:
     *  - Retrieve product packaging units count for a given product packaging unit type.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return int
     */
    public function getCountProductPackagingUnitsForType(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): int;

    /**
     * Specification:
     *  - Creates product packaging unit type.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    public function createProductPackagingUnitType(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ProductPackagingUnitTypeTransfer;

    /**
     * Specification:
     *  - Updates product packaging unit type.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    public function updateProductPackagingUnitType(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ProductPackagingUnitTypeTransfer;

    /**
     * Specification:
     *  - Deletes a product packaging unit type.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return bool
     */
    public function deleteProductPackagingUnitType(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): bool;
}
