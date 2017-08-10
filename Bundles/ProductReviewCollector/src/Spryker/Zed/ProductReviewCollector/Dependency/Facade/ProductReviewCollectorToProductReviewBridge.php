<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewCollector\Dependency\Facade;

class ProductReviewCollectorToProductReviewBridge implements ProductReviewCollectorToProductReviewInterface
{

    /**
     * @var \Spryker\Zed\ProductReview\Business\ProductReviewFacadeInterface
     */
    protected $productReviewFacade;

    /**
     * @param \Spryker\Zed\ProductReview\Business\ProductReviewFacadeInterface $productReviewFacade
     */
    public function __construct($productReviewFacade)
    {
        $this->productReviewFacade = $productReviewFacade;
    }

    /**
     * @param int $idProductReview
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getCombinedProductReviewImageSets($idProductReview, $idLocale)
    {
        return $this->productReviewFacade->getCombinedProductReviewImageSets($idProductReview, $idLocale);
    }

}
