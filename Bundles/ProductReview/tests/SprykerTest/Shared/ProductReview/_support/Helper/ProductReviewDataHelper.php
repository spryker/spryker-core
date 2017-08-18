<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ProductReview\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductReviewBuilder;
use Generated\Shared\Transfer\ProductReviewTransfer;
use SprykerTest\Shared\Testify\Helper\DependencyHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductReviewDataHelper extends Module
{

    use DependencyHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\ProductReviewTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function haveProductReview($override = [])
    {
        $productAbstractTransfer = $this->getModule("\SprykerTest\Shared\Product\Helper\ProductDataHelper")->haveProductAbstract();
        $customerTransfer = $this->getModule("\SprykerTest\Shared\Customer\Helper\CustomerDataHelper")->haveCustomer();
        $localeTransfer = $this->getModule("\SprykerTest\Zed\Locale\Helper\LocaleDataHelper")->haveLocale();

        $override[ProductReviewTransfer::FK_PRODUCT_ABSTRACT] = $productAbstractTransfer->getIdProductAbstract();
        $override[ProductReviewTransfer::CUSTOMER_REFERENCE] = $customerTransfer->getCustomerReference();
        $override[ProductReviewTransfer::FK_LOCALE] = $localeTransfer->getIdLocale();

        $productReviewTransfer = (new ProductReviewBuilder($override))->build();

        return $productReviewTransfer;
    }

}
