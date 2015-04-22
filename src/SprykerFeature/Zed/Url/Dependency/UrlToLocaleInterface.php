<?php

namespace SprykerFeature\Zed\Url\Dependency;

use SprykerEngine\Zed\Locale\Business\Exception\MissingLocaleException;

interface UrlToLocaleInterface
{
    /**
     * @param string $localeName
     *
     * @return int
     * @throws MissingLocaleException
     */
    public function getIdLocale($localeName);

    /**
     * @return string
     */
    public function getCurrentLocale();

    /**
     * @return int
     */
    public function getCurrentIdLocale();
}
