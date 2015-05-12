<?php

namespace SprykerFeature\Zed\ProductCategory\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;

interface ProductCategoryToLocaleInterface
{
    /**
     * @return LocaleTransfer
     */
    public function getCurrentLocale();
}
