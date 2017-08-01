<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Business\Model;

use Exception;
use Spryker\Zed\ProductReview\Persistence\ProductReviewQueryContainerInterface;

class ProductReviewEntityReader implements ProductReviewEntityReaderInterface
{

    /**
     * @var \Spryker\Zed\ProductReview\Persistence\ProductReviewQueryContainerInterface
     */
    protected $productReviewQueryContainer;

    /**
     * @param \Spryker\Zed\ProductReview\Persistence\ProductReviewQueryContainerInterface $productReviewQueryContainer
     */
    public function __construct(ProductReviewQueryContainerInterface $productReviewQueryContainer)
    {
        $this->productReviewQueryContainer = $productReviewQueryContainer;
    }

    /**
     * @param int $idProductReview
     *
     * @throws \Exception
     *
     * @return \Orm\Zed\ProductReview\Persistence\SpyProductReview
     */
    public function getProductReviewEntityById($idProductReview)
    {
        $productReviewEntity = $this->productReviewQueryContainer
            ->queryProductReviewById($idProductReview)
            ->findOne();

        if (!$productReviewEntity) {
            throw new Exception('Invalid product review'); // TODO: fix exception
        }

        return $productReviewEntity;
    }

}
