<?php

namespace SprykerFeature\Zed\ProductCategory\Dependency\Facade;

use SprykerEngine\Shared\Locale\Dto\LocaleDto;

interface ProductCategoryToCategoryInterface
{

    /**
     * @param string $categoryName
     * @param LocaleDto $locale
     *
     * @return bool
     */
    public function hasCategoryNode($categoryName, LocaleDto $locale);

    /**
     * @param string $categoryName
     * @param LocaleDto $locale
     *
     * @return int
     */
    public function getCategoryNodeIdentifier($categoryName, LocaleDto $locale);
}
