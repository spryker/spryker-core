<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;

interface CategoryToLocaleInterface
{

    /**
     * @return LocaleTransfer
     */
    public function getCurrentLocale();

}
