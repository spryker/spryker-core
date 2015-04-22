<?php

namespace SprykerFeature\Zed\Category\Dependency\Facade;

use SprykerEngine\Zed\Locale\Business\Exception\MissingLocaleException;

interface CategoryToLocaleInterface
{
    /**
     * @return string
     */
    public function getCurrentLocale();

    /**
     * @param string $localeName
     *
     * @return int
     * @throws MissingLocaleException
     */
    public function getIdLocale($localeName);

    /**
     * @return int
     */
    public function getCurrentIdLocale();
}
