<?php

namespace SprykerFeature\Zed\ProductSearch\Dependency\Facade;

use SprykerEngine\Shared\Dto\LocaleDto;

interface ProductSearchToLocaleInterface
{
    /**
     * @return LocaleDto
     */
    public function getCurrentLocale();
}
