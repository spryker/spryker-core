<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Business;

use Generated\Shared\Transfer\ProductReviewTransfer;

interface ProductReviewFacadeInterface
{

    /**
     * Specification:
     * - Stores provided product review in persistent storage with pending status.
     *   - Checks if provided rating in transfer object does not exceed configured limit
     * - Returns the provided transfer object updated with the stored entity's data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReviewTransfer
     */
    public function createProductReview(ProductReviewTransfer $productReviewTransfer);

    /**
     * Specification:
     * - Retrieves the product review from persistent storage that matches the provided id in the transfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReviewTransfer|null
     */
    public function findProductReview(ProductReviewTransfer $productReviewTransfer);

    /**
     * Specification:
     * - Updates product review's status in persistent storage that matches the provided id in the transfer object.
     * - Returns the provided transfer object updated with the saved entity's data.
     * - Touches "product review"
     *   - As "active" if status was updated to "approved".
     *   - As "deleted" if status was updated to "rejected" or "pending".
     * - Touches "product review abstract" entities as "active".
     * - Touches product abstract
     *   - Touches abstract product and all its variants.
     *   - Touches related "product_abstract", "product_concrete", and "attribute_map" entries.
     *   - Used touch event status (active, inactive) depends on the current status of the product and its variants.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReviewTransfer
     */
    public function updateProductReviewStatus(ProductReviewTransfer $productReviewTransfer);

    /**
     * Specification:
     * - Permanently deletes the product review from persistent storage that matches the provided id in the transfer object.
     * - Touches "product review" entries as "delete".
     * - Touches "product review abstract" entries as "active".
     * - Touches product abstract
     *   - Touches abstract product and all its variants.
     *   - Touches related "product_abstract", "product_concrete", and "attribute_map" entries.
     *   - Used touch event status (active, inactive) depends on the current status of the product and its variants.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return void
     */
    public function deleteProductReview(ProductReviewTransfer $productReviewTransfer);

}
