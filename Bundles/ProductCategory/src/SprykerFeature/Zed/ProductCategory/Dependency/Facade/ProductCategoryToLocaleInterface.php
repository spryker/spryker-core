<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;

interface ProductCategoryToLocaleInterface
{

    /**
     * @return LocaleTransfer
     *
     * @return void
     */
    public function getCurrentLocale();

}
