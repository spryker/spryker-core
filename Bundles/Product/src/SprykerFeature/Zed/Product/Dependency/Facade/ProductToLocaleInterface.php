<?php

namespace SprykerFeature\Zed\Product\Dependency\Facade;

use SprykerEngine\Shared\Dto\LocaleDto;

interface ProductToLocaleInterface
{
    /**
     * @return LocaleDto
     */
    public function getCurrentLocale();
}
