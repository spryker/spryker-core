<?php

namespace SprykerFeature\Zed\ProductOptions\Dependency\Facade;

use SprykerEngine\Zed\Locale\Business\Exception\MissingLocaleException;
use Generated\Shared\Transfer\LocaleTransfer;

interface ProductOptionsToLocaleInterface
{

    /**
     * @param string $localeName
     *
     * @return bool
     */
    public function hasLocale($localeName);

    /**
     * @param string $localeName
     *
     * @return LocaleTransfer
     * @throws MissingLocaleException
     */
    public function getLocale($localeName);
}
