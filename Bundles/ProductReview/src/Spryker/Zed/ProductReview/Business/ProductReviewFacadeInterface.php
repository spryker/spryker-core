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
     * - TODO: add spec
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
     * - TODO: add spec
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
     * - TODO: add spec
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReviewTransfer
     */
    public function updateProductReview(ProductReviewTransfer $productReviewTransfer);

    /**
     * Specification:
     * - TODO: add spec
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return void
     */
    public function deleteProductReview(ProductReviewTransfer $productReviewTransfer);

}
