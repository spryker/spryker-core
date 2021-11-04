<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductReview;

use Codeception\Actor;
use Generated\Shared\Transfer\BulkProductReviewSearchRequestTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Client\ProductReview\ProductReviewClientInterface getClient()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductReviewClientTester extends Actor
{
    use _generated\ProductReviewClientTesterActions;

    /**
     * @return array<array<array<array<int>>>>
     */
    public function createClinetSearchMockResponse(): array
    {
        return [
            'productAggregation' => [
                1 => [
                    'ratingAggregation' => [
                        5 => 3,
                        2 => 1,
                    ],
                ],
                2 => [
                    'ratingAggregation' => [
                        5 => 3,
                        1 => 10,
                    ],
                ],
                3 => [
                    'ratingAggregation' => [
                        5 => 130,
                        4 => 33,
                        3 => 21,
                        2 => 10,
                        1 => 5,
                    ],
                ],
            ],
        ];
    }

    /**
     * @param int $id
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function buildProductViewTranseferById(int $id): ProductViewTransfer
    {
        $prodcutViewTransfer = new ProductViewTransfer();
        $prodcutViewTransfer->setIdProductAbstract($id);

        return $prodcutViewTransfer;
    }

    /**
     * @return array<\Generated\Shared\Transfer\ProductViewTransfer>
     */
    public function createProductViews(): array
    {
        return [
            1 => $this->buildProductViewTranseferById(1),
            2 => $this->buildProductViewTranseferById(2),
            3 => $this->buildProductViewTranseferById(3),
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\BulkProductReviewSearchRequestTransfer
     */
    public function createBulkProductReviewSearchRequestTransfer(): BulkProductReviewSearchRequestTransfer
    {
        $bulkProductReviewSearchRequestTransfer = new BulkProductReviewSearchRequestTransfer();
        $bulkProductReviewSearchRequestTransfer->setProductAbstractIds(array_keys($this->createProductViews()));
        $bulkProductReviewSearchRequestTransfer->setFilter(new FilterTransfer());

        return $bulkProductReviewSearchRequestTransfer;
    }
}
