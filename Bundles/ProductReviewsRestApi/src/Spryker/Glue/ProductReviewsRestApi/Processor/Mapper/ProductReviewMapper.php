<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductReviewTransfer;
use Generated\Shared\Transfer\RestProductReviewsAttributesTransfer;

class ProductReviewMapper implements ProductReviewMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     * @param \Generated\Shared\Transfer\RestProductReviewsAttributesTransfer $restProductReviewsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductReviewsAttributesTransfer
     */
    public function mapProductReviewTransferToRestProductReviewsAttributesTransfer(
        ProductReviewTransfer $productReviewTransfer,
        RestProductReviewsAttributesTransfer $restProductReviewsAttributesTransfer
    ): RestProductReviewsAttributesTransfer {
        return $restProductReviewsAttributesTransfer->fromArray(
            $productReviewTransfer->toArray(),
            true
        );
    }
}
