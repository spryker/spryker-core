<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Product\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;

interface ProductToLocaleInterface
{

    /**
     * @return LocaleTransfer
     */
    public function getCurrentLocale();

    /**
     * @return array
     */
    public function getAvailableLocales();

    /**
     * @param string $localeName
     *
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     *
     * @return LocaleTransfer
     */
    public function getLocale($localeName);

}
