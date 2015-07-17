<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductSearch\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;

interface ProductSearchToLocaleInterface
{

    /**
     * @return LocaleTransfer
     */
    public function getCurrentLocale();

}
