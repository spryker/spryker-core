<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Communication\Controller\Mapper;

use Generated\Shared\Transfer\ProductReviewRequestTransfer;

interface CustomerReviewSubmitMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductReviewRequestTransfer $productReviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReviewTransfer
     */
    public function mapRequestTransfer(ProductReviewRequestTransfer $productReviewRequestTransfer);
}
