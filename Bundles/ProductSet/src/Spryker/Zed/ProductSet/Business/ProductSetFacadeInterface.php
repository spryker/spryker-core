<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business;

use Generated\Shared\Transfer\ProductSetTransfer;

interface ProductSetFacadeInterface
{

    /**
     * Specification:
     * - Persist new product set entity into database.
     * - The persisted position of the products are defined by the order they are listed in the transfer object.
     * - Persist product set data entities into database for each provided locale.
     * - Create URL entities for the product set for each provided locale.
     * - Create product image set and product image entities for each provided locale.
     * - The returned transfer contains the ID of the created product set entity.
     * - Touches "product_set" entity as active.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    public function createProductSet(ProductSetTransfer $productSetTransfer);

    /**
     * Specification:
     * - Finds existing product set in database.
     * - Returns the product set with the abstract product IDs if the set exists and has any products in it, NULL otherwise.
     * - Product IDs are returned in order they were persisted.
     * - Returns localized product set data, URLs and product image sets.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer|null
     */
    public function findProductSet(ProductSetTransfer $productSetTransfer);

    /**
     * Specification:
     * - Updates existing product set in database.
     * - The persisted position of the products are defined by the order they are listed in the transfer object.
     * - Updates localized product set data, URLs and product image sets
     * - Touches "product_set" entity as active.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    public function updateProductSet(ProductSetTransfer $productSetTransfer);

    /**
     * Specification:
     * - Extends existing product set in database with the given product IDs.
     * - The persisted position of the new products will follow the existing ones and are defined by the order they are listed in the transfer object.
     * - Touches "product_set" entity as active.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    public function extendProductSet(ProductSetTransfer $productSetTransfer);

    /**
     * Specification:
     * - Removes the given product IDs from an existing product set.
     * - The position of the remaining products will be recalculated to avoid holes.
     * - Touches "product_set" entity as active.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    public function removeFromProductSet(ProductSetTransfer $productSetTransfer);

    /**
     * Specification:
     * - Removes existing product set from database.
     * - Removes related localized product set data, URLs and product image sets from database.
     * - Touches "product_set" entity as deleted.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return void
     */
    public function deleteProductSet(ProductSetTransfer $productSetTransfer);

    /**
     * Specification:
     * - Updates weights of existing product sets in database.
     *
     * @api
     *
     * @param ProductSetTransfer[] $productSetTransfers
     *
     * @return void
     */
    public function reorderProductSets(array $productSetTransfers);

}
