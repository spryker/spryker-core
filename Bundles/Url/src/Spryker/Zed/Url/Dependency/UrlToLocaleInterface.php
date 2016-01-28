<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Url\Dependency;

use Generated\Shared\Transfer\LocaleTransfer;

interface UrlToLocaleInterface
{

    /**
     * @param string $localeName
     *
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     *
     * @return LocaleTransfer
     */
    public function getLocale($localeName);

    /**
     * @return LocaleTransfer
     */
    public function getCurrentLocale();

}
