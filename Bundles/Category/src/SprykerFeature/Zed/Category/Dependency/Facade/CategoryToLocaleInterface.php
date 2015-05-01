<?php

namespace SprykerFeature\Zed\Category\Dependency\Facade;

use SprykerEngine\Shared\Dto\LocaleDto;

interface CategoryToLocaleInterface
{
    /**
     * @return LocaleDto
     */
    public function getCurrentLocale();
}
