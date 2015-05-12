<?php

namespace SprykerFeature\Zed\Url\Dependency;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Zed\Locale\Business\Exception\MissingLocaleException;

interface UrlToLocaleInterface
{
    /**
     * @param string $localeName
     *
     * @return LocaleTransfer
     * @throws MissingLocaleException
     */
    public function getLocale($localeName);

    /**
     * @return LocaleTransfer
     */
    public function getCurrentLocale();
}
