<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Category\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;

interface CategoryToLocaleInterface
{

    /**
     * @return LocaleTransfer
     */
    public function getCurrentLocale();

    /**
     * @param string $localeName
     *
     * @return LocaleTransfer
     */
    public function getLocale($localeName);

    /**
     * @return array
     */
    public function getAvailableLocales();

}
