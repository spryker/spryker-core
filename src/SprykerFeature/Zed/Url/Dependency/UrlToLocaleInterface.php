<?php

namespace SprykerFeature\Zed\Url\Dependency;

use SprykerEngine\Shared\Dto\LocaleDto;
use SprykerEngine\Zed\Locale\Business\Exception\MissingLocaleException;

interface UrlToLocaleInterface
{
    /**
     * @param string $localeName
     *
     * @return LocaleDto
     * @throws MissingLocaleException
     */
    public function getLocale($localeName);

    /**
     * @return LocaleDto
     */
    public function getCurrentLocale();
}
