<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductReviewSearch\Persistence;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Generated\Shared\Transfer\ProductPayloadTransfer;
use Orm\Zed\ProductReview\Persistence\Map\SpyProductReviewTableMap;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\ProductReviewSearch\Persistence\ProductReviewSearchQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductReviewSearch
 * @group Persistence
 * @group ProductReviewSearchQueryContainerTest
 * Add your own group annotations below this line
 */
class ProductReviewSearchQueryContainerTest extends Unit
{
    protected const TEST_LOCALE_NAME = 'xxx';

    /**
     * @var \SprykerTest\Zed\ProductReviewSearch\ProductReviewSearchPersistenceTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testQueryProductReviewRatingByIdAbstractProductReturnsCorrectData(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale([
            LocaleTransfer::LOCALE_NAME => static::TEST_LOCALE_NAME,
        ]);

        $customerTransfer = $this->tester->haveCustomer();

        $productAbstractTransfer = $this->tester->haveProductAbstract();

        $productReviewTransfer1 = $this->tester->haveApprovedCustomerReviewForAbstractProduct(
            $localeTransfer->getIdLocale(),
            $customerTransfer->getCustomerReference(),
            $productAbstractTransfer->getIdProductAbstract(),
            SpyProductReviewTableMap::COL_STATUS_APPROVED
        );
        $productReviewTransfer2 = $this->tester->haveApprovedCustomerReviewForAbstractProduct(
            $localeTransfer->getIdLocale(),
            $customerTransfer->getCustomerReference(),
            $productAbstractTransfer->getIdProductAbstract(),
            SpyProductReviewTableMap::COL_STATUS_APPROVED
        );
        $productAbstractRatingAverage = new Decimal(($productReviewTransfer1->getRating() + $productReviewTransfer2->getRating()) / 2);

        // Act
        $result = (new ProductReviewSearchQueryContainer())->queryProductReviewRatingByIdAbstractProduct($productAbstractTransfer->getIdProductAbstract())
            ->find()->toArray();

        // Assert
        $this->assertCount(1, $result);
        $this->assertEquals($productAbstractTransfer->getIdProductAbstract(), $result[0][ProductPageSearchTransfer::ID_PRODUCT_ABSTRACT]);
        $this->assertTrue($productAbstractRatingAverage->equals($result[0][ProductPayloadTransfer::AVERAGE_RATING]));
        $this->assertEquals(2, $result[0][ProductPayloadTransfer::REVIEW_COUNT]);
    }
}
