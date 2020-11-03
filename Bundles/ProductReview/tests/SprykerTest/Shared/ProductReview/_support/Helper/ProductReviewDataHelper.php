<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ProductReview\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductReviewBuilder;
use Generated\Shared\Transfer\ProductReviewTransfer;
use Orm\Zed\ProductReview\Persistence\Map\SpyProductReviewTableMap;
use Orm\Zed\ProductReview\Persistence\SpyProductReview;
use Orm\Zed\ProductReview\Persistence\SpyProductReviewQuery;
use SprykerTest\Shared\Customer\Helper\CustomerDataHelper;
use SprykerTest\Shared\Product\Helper\ProductDataHelper;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\DependencyHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use SprykerTest\Zed\Locale\Helper\LocaleDataHelper;

class ProductReviewDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use DependencyHelperTrait;
    use LocatorHelperTrait;

    public const NAMESPACE_ROOT = '\\';

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\ProductReviewTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function haveProductReview(array $override = [])
    {
        $productAbstractTransfer = $this->getModule(static::NAMESPACE_ROOT . ProductDataHelper::class)->haveProductAbstract();
        $customerTransfer = $this->getModule(static::NAMESPACE_ROOT . CustomerDataHelper::class)->haveCustomer();
        $localeTransfer = $this->getModule(static::NAMESPACE_ROOT . LocaleDataHelper::class)->haveLocale();

        $override[ProductReviewTransfer::FK_PRODUCT_ABSTRACT] = $productAbstractTransfer->getIdProductAbstract();
        $override[ProductReviewTransfer::CUSTOMER_REFERENCE] = $customerTransfer->getCustomerReference();
        $override[ProductReviewTransfer::FK_LOCALE] = $localeTransfer->getIdLocale();

        $productReviewTransfer = (new ProductReviewBuilder($override))->build();

        return $productReviewTransfer;
    }

    /**
     * @param int $idLocale
     * @param string $customerReference
     * @param int $idProductAbstract
     * @param string $productReviewStatus
     *
     * @return \Generated\Shared\Transfer\ProductReviewTransfer
     */
    public function haveApprovedCustomerReviewForAbstractProduct(
        int $idLocale,
        string $customerReference,
        int $idProductAbstract,
        string $productReviewStatus = SpyProductReviewTableMap::COL_STATUS_PENDING
    ): ProductReviewTransfer {
        $productReviewTransfer = (new ProductReviewBuilder([
            ProductReviewTransfer::STATUS => $productReviewStatus,
            ProductReviewTransfer::FK_LOCALE => $idLocale,
            ProductReviewTransfer::CUSTOMER_REFERENCE => $customerReference,
            ProductReviewTransfer::FK_PRODUCT_ABSTRACT => $idProductAbstract,
        ]))->build();

        $productReviewTransfer = $this->saveProductReview($productReviewTransfer);

        $this->getDataCleanupHelper()->addCleanup(function () use ($productReviewTransfer): void {
            $this->removeProductReview($productReviewTransfer);
        });

        return $productReviewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReviewTransfer
     */
    protected function saveProductReview(ProductReviewTransfer $productReviewTransfer): ProductReviewTransfer
    {
        $productReviewEntity = new SpyProductReview();
        $productReviewEntity->fromArray($productReviewTransfer->toArray());
        $productReviewEntity->save();

        return $productReviewTransfer->fromArray($productReviewEntity->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return void
     */
    protected function removeProductReview(ProductReviewTransfer $productReviewTransfer): void
    {
        SpyProductReviewQuery::create()
            ->filterByIdProductReview($productReviewTransfer->getIdProductReview())
            ->delete();
    }
}
