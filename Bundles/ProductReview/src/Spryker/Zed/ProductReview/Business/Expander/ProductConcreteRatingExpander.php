<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Business\Expander;

use Spryker\Zed\ProductReview\Persistence\ProductReviewRepositoryInterface;
use Spryker\Zed\ProductReview\ProductReviewConfig;

class ProductConcreteRatingExpander implements ProductConcreteRatingExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductReview\Persistence\ProductReviewRepositoryInterface
     */
    protected $productReviewRepository;

    /**
     * @param \Spryker\Zed\ProductReview\Persistence\ProductReviewRepositoryInterface $productReviewRepository
     */
    public function __construct(ProductReviewRepositoryInterface $productReviewRepository)
    {
        $this->productReviewRepository = $productReviewRepository;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function expandProductConcretesWithRating(array $productConcreteTransfers): array
    {
        $ratingsByProductAbstractIds = $this->getRatingsByProductAbstractIds($productConcreteTransfers);

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            if (!isset($ratingsByProductAbstractIds[$productConcreteTransfer->getFkProductAbstract()])) {
                continue;
            }

            $rating = round(
                $ratingsByProductAbstractIds[$productConcreteTransfer->getFkProductAbstract()],
                ProductReviewConfig::RATING_PRECISION,
            );

            $productConcreteTransfer->setRating($rating);
        }

        return $productConcreteTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<int, float>
     */
    protected function getRatingsByProductAbstractIds(array $productConcreteTransfers): array
    {
        $productAbstractIds = [];

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productAbstractIds[] = $productConcreteTransfer->getFkProductAbstract();
        }

        return $this->productReviewRepository
            ->getProductRatingsByProductAbstractIds(array_unique($productAbstractIds));
    }
}
