<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroup\Business;

use Generated\Shared\Transfer\ProductGroupTransfer;

interface ProductGroupFacadeInterface
{
    /**
     * Specification:
     * - Persist new product group entity into database.
     * - The returned transfer contains the ID of the created product group entity.
     * - The persisted position of the products are defined by the order they are listed in the transfer object.
     * - Touches "product_group" and all related "product_abstract_groups" touch entities as active.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ProductGroupTransfer
     */
    public function createProductGroup(ProductGroupTransfer $productGroupTransfer);

    /**
     * Specification:
     * - Finds existing product group in database.
     * - Returns the product group with the abstract product IDs if the group exists and has any products in it, NULL otherwise.
     * - Product IDs are returned in order they were persisted.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ProductGroupTransfer|null
     */
    public function findProductGroup(ProductGroupTransfer $productGroupTransfer);

    /**
     * Specification:
     * - Updates existing product group in database.
     * - The persisted position of the products are defined by the order they are listed in the transfer object.
     * - Touches "product_group" and all related "product_abstract_groups" touch entities as active.
     * - Removed product abstract group entities will be touched as deleted.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ProductGroupTransfer
     */
    public function updateProductGroup(ProductGroupTransfer $productGroupTransfer);

    /**
     * Specification:
     * - Extends existing product group in database with the given product IDs.
     * - The persisted position of the new products will follow the existing ones and are defined by the order they are listed in the transfer object.
     * - Touches "product_group" and all related "product_abstract_groups" touch entities.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ProductGroupTransfer
     */
    public function extendProductGroup(ProductGroupTransfer $productGroupTransfer);

    /**
     * Specification:
     * - Removes the given product IDs from an existing product group.
     * - The position of the remaining products will be recalculated to avoid holes.
     * - Touches "product_group" entity as active and related "product_abstract_groups" entities as deleted.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ProductGroupTransfer
     */
    public function removeFromProductGroup(ProductGroupTransfer $productGroupTransfer);

    /**
     * Specification:
     * - Removes existing product group from database.
     * - Touches "product_group" and all related "product_abstract_groups" touch entities as deleted.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return void
     */
    public function deleteProductGroup(ProductGroupTransfer $productGroupTransfer);
}
