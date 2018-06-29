<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ProductPackagingLeadProductTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

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
     * - Retrieves the list of infrastructural packaging unit type names.
     *
     * @api
     *
     * @return string[]
     */
    public function getInfrastructuralPackagingUnitTypeNames(): array;

    /**
     * Specification:
     *  - Returns Default Packaging Unit Type Name
     *
     * @api
     *
     * @return string
     */
    public function getDefaultPackagingUnitTypeName(): string;

    /**
     * Specification:
     * - Retrieves a product packaging unit type by the provided name within the provided transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    public function findProductPackagingUnitTypeByName(
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
     * - Retrieves a product packaging unit type by provided product packaging type ID within the provided transfer.
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
     * - Retrieves the count of existing packaging units for a given product packaging unit type.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return int
     */
    public function countProductPackagingUnitsByTypeId(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): int;

    /**
     * Specification:
     * - Retrieve productAbstractId by productPackagingUnitTypeIds.
     *
     * @api
     *
     * @param array $productPackagingUnitTypeIds
     *
     * @return array
     */
    public function getIdProductAbstractsByIdProductPackagingUnitTypes(array $productPackagingUnitTypeIds): array;

    /**
     * Specification:
     * - Creates product packaging unit type.
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
     * - Updates product packaging unit type.
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
     * - Deletes a product packaging unit type.
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

    /**
     * Specification:
     * - Expands CartChangeTransfer with amountLeadProduct.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartChangeWithAmountLeadProduct(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer;

    /**
     * Specification:
     *
     * - Checks if items which being added to cart is available.
     * - For packaging units it checks the lead product also if `hasLeadProduct`.
     * - Even if same lead product added separatelly from packaging unit availability is checked together.
     * - Sets error message if not available
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function preCheckCartAvailability(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer;

    /**
     * Specification:
     *
     * - Checks if items which being added to cart is available.
     * - For packaging units it checks the lead product also if `hasLeadProduct`.
     * - Even if same lead product added separatelly from packaging unit availability is checked together.
     * - Sets error message if not available
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkoutAvailabilityPreCheck(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): bool;

    /**
     * Specification:
     *  - Updates the availability of the lead product of a given ProductPackagingUnit.
     *
     * @api
     *
     * @param string $sku
     *
     * @return void
     */
    public function updateProductPackagingUnitLeadProductAvailability(string $sku): void;
}
