<?php

namespace SprykerFeature\Zed\ProductCategory\Dependency\Facade;

use SprykerEngine\Shared\Locale\Dto\LocaleDto;

interface ProductCategoryToLocaleInterface
{
    /**
     * @return LocaleDto
     */
    public function getCurrentLocale();
}
