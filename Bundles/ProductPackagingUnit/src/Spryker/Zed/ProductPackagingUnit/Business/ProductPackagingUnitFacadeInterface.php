<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business;

use Generated\Shared\Transfer\ProductPackagingLeadProductTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;

interface ProductPackagingUnitFacadeInterface
{
    /**
     * Specification:
     * - Add infrastructural packaging unit type list to persistence.
     *
     * @api
     *
     * @return void
     */
    public function installProductPackagingUnitTypes(): void;

    /**
     * Specification:
     *  - Retrieve a product packaging unit type by ProductPackagingUnitTypeTransfer::name in the transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    public function getProductPackagingUnitTypeByName(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ProductPackagingUnitTypeTransfer;

    /**
     * Specification:
     * - Retrieves a product packaging lead product by provided product abstract ID.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductPackagingLeadProductTransfer|null
     */
    public function getProductPackagingLeadProductByIdProductAbstract(
        int $idProductAbstract
    ): ?ProductPackagingLeadProductTransfer;

    /**
     * Specification:
     * - Returns default packaging unit type name.
     *
     * @api
     *
     * @return string
     */
    public function getDefaultPackagingUnitTypeName(): string;

    /**
     * Specification:
     * - Retrieve productAbstractId by productPackagingUnitTypeIds.
     *
     * @param array $productPackagingUnitTypeIds
     *
     * @return array
     */
    public function getIdProductAbstractsByIdProductPackagingUnitTypes(array $productPackagingUnitTypeIds): array;
}
