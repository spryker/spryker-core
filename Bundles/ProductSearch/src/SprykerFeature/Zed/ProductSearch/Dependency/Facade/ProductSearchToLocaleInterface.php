<?php

namespace SprykerFeature\Zed\ProductSearch\Dependency\Facade;

use SprykerEngine\Shared\Locale\Dto\LocaleDto;

interface ProductSearchToLocaleInterface
{
    /**
     * @return LocaleDto
     */
    public function getCurrentLocale();
}
