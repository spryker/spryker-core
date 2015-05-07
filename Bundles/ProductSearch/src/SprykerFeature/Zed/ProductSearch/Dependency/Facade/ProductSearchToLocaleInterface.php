<?php

namespace SprykerFeature\Zed\ProductSearch\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;

interface ProductSearchToLocaleInterface
{
    /**
     * @return LocaleTransfer
     */
    public function getCurrentLocale();
}
