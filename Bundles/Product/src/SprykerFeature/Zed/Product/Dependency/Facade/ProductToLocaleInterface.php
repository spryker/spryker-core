<?php

namespace SprykerFeature\Zed\Product\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;

interface ProductToLocaleInterface
{
    /**
     * @return LocaleTransfer
     */
    public function getCurrentLocale();
}
