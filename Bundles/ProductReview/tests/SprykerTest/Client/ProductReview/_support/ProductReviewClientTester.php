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
 * @SuppressWarnings(\SprykerTest\Client\ProductReview\PHPMD)
 */
class ProductReviewClientTester extends Actor
{
    use _generated\ProductReviewClientTesterActions;

    /**
     * @param list<int> $productAbstractIds
     *
     * @return list<\Generated\Shared\Transfer\ProductViewTransfer>
     */
    public function createProductViewTransfers(array $productAbstractIds): array
    {
        $productViewTransfers = [];
        foreach ($productAbstractIds as $idProductAbstract) {
            $productViewTransfers[] = (new ProductViewTransfer())->setIdProductAbstract($idProductAbstract);
        }

        return $productViewTransfers;
    }

    /**
     * @param list<int> $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\BulkProductReviewSearchRequestTransfer
     */
    public function createBulkProductReviewSearchRequestTransfer(array $productAbstractIds): BulkProductReviewSearchRequestTransfer
    {
        $bulkProductReviewSearchRequestTransfer = new BulkProductReviewSearchRequestTransfer();
        $bulkProductReviewSearchRequestTransfer->setProductAbstractIds($productAbstractIds);
        $bulkProductReviewSearchRequestTransfer->setFilter(new FilterTransfer());

        return $bulkProductReviewSearchRequestTransfer;
    }
}
