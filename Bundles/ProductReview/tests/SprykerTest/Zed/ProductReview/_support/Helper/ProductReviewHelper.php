<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductReview\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ReviewBuilder;
use Generated\Shared\Transfer\AddReviewsTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ReviewTransfer;
use Orm\Zed\ProductReview\Persistence\SpyProductReviewQuery;
use Ramsey\Uuid\Uuid;
use SprykerTest\Shared\Product\Helper\ProductDataHelper;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class ProductReviewHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @return \Generated\Shared\Transfer\AddReviewsTransfer
     */
    public function haveAddReviewTransferWithValidProductAndLocale(): AddReviewsTransfer
    {
        $productIdentifier = Uuid::uuid4()->toString();
        $this->haveProductWithIdentifier($productIdentifier);

        $addReviewsTransfer = new AddReviewsTransfer();
        $addReviewsTransfer->addReview($this->haveReviewTransfer([
            ReviewTransfer::PRODUCT_IDENTIFIER => $productIdentifier,
            ReviewTransfer::LOCALE => 'en_US',
        ]));

        return $addReviewsTransfer;
    }

    /**
     * We expect that this transfer is used to create a review and we make sure that no matter it was created or not that if it was created it gets cleaned up.
     *
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\ReviewTransfer
     */
    public function haveReviewTransfer(array $seed = []): ReviewTransfer
    {
        $reviewTransfer = (new ReviewBuilder($seed))->build();
        $reviewTransfer->setStatus('approved');

        $this->getDataCleanupHelper()->addCleanup(function () use ($reviewTransfer) {
            SpyProductReviewQuery::create()->filterByNickname($reviewTransfer->getNickname())->delete();
        });

        return $reviewTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\AddReviewsTransfer
     */
    public function haveAddReviewTransferWithoutValidProduct(): AddReviewsTransfer
    {
        $addReviewsTransfer = new AddReviewsTransfer();
        $addReviewsTransfer->addReview($this->haveReviewTransfer());

        return $addReviewsTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\AddReviewsTransfer
     */
    public function haveAddReviewTransferWithoutValidLocale(): AddReviewsTransfer
    {
        $productIdentifier = Uuid::uuid4()->toString();

        $addReviewsTransfer = new AddReviewsTransfer();
        $addReviewsTransfer->addReview($this->haveReviewTransfer([
            ReviewTransfer::PRODUCT_IDENTIFIER => $productIdentifier,
            ReviewTransfer::LOCALE => 'not existing locale',
        ]));

        return $addReviewsTransfer;
    }

    /**
     * Just for internal use, to make sure that we have a valid product for a mocked review.
     *
     * @param string $productIdentifier
     *
     * @return void
     */
    protected function haveProductWithIdentifier(string $productIdentifier)
    {
        $this->getProductHelper()->haveFullProduct([], [ProductAbstractTransfer::SKU => $productIdentifier]);
    }

    /**
     * @return \SprykerTest\Shared\Product\Helper\ProductDataHelper
     */
    protected function getProductHelper(): ProductDataHelper
    {
        return $this->getModule('\\' . ProductDataHelper::class);
    }

    /**
     * @param \Generated\Shared\Transfer\ReviewTransfer $reviewTransfer
     *
     * @return void
     */
    public function assertReviewExists(ReviewTransfer $reviewTransfer): void
    {
        $productReviewEntity = SpyProductReviewQuery::create()->findOneByNickname($reviewTransfer->getNickname());

        $this->assertNotNull($productReviewEntity, sprintf('Expected to find a ProductReview from %s but no Review was found.', $reviewTransfer->getNickname()));
    }

    /**
     * @param \Generated\Shared\Transfer\ReviewTransfer $reviewTransfer
     *
     * @return void
     */
    public function assertReviewNotExists(ReviewTransfer $reviewTransfer): void
    {
        $numberOfFoundReviews = SpyProductReviewQuery::create()->filterByNickname($reviewTransfer->getNickname())->count();

        $this->assertSame(0, $numberOfFoundReviews, sprintf('Expected to not have a ProductReview from %s but Review was found.', $reviewTransfer->getNickname()));
    }
}
