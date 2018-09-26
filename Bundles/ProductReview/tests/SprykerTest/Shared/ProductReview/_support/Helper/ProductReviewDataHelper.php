<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ProductReview\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductReviewBuilder;
use Generated\Shared\Transfer\ProductReviewTransfer;
use SprykerTest\Shared\Customer\Helper\CustomerDataHelper;
use SprykerTest\Shared\Product\Helper\ProductDataHelper;
use SprykerTest\Shared\Testify\Helper\DependencyHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use SprykerTest\Zed\Locale\Helper\LocaleDataHelper;

class ProductReviewDataHelper extends Module
{
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
}
