<?php

namespace SprykerFeature\Zed\Category\Dependency\Facade;

use SprykerEngine\Shared\Locale\Dto\LocaleDto;

interface CategoryToLocaleInterface
{
    /**
     * @return LocaleDto
     */
    public function getCurrentLocale();
}
