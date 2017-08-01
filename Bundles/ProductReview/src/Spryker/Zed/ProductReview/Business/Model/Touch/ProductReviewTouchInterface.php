<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Business\Model\Touch;

use Generated\Shared\Transfer\ProductReviewTransfer;

interface ProductReviewTouchInterface
{

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return bool
     */
    public function touchProductReviewActive(ProductReviewTransfer $productReviewTransfer);

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return bool
     */
    public function touchProductReviewDeleted(ProductReviewTransfer $productReviewTransfer);

}
