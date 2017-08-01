<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Business;

use Generated\Shared\Transfer\ProductReviewTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductReview\Business\ProductReviewBusinessFactory getFactory()
 */
class ProductReviewFacade extends AbstractFacade implements ProductReviewFacadeInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReviewTransfer
     */
    public function createProductReview(ProductReviewTransfer $productReviewTransfer)
    {
        return $this->getFactory()
            ->createProductReviewCreator()
            ->createProductReview($productReviewTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReviewTransfer|null
     */
    public function findProductReview(ProductReviewTransfer $productReviewTransfer)
    {
        return $this->getFactory()
            ->createProductReviewReader()
            ->findProductReview($productReviewTransfer);
    }

    // TODO: update + touch
    // TODO: delete + touch

    // TODO: product review search collector collects reviews to separated index type (in "ProductReviewCollector" module)

}
