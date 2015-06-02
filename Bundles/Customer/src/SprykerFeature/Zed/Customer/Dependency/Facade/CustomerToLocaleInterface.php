<?php

namespace SprykerFeature\Zed\Customer\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;

interface CustomerToLocaleInterface
{

    /**
     * @return LocaleTransfer
     */
    public function getCurrentLocale();
}
