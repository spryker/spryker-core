<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Url\Dependency;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Zed\Locale\Business\Exception\MissingLocaleException;

interface UrlToLocaleInterface
{

    /**
     * @param string $localeName
     *
     * @throws MissingLocaleException
     *
     * @return LocaleTransfer
     */
    public function getLocale($localeName);

    /**
     * @return LocaleTransfer
     */
    public function getCurrentLocale();

}
