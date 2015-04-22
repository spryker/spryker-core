<?php

namespace SprykerFeature\Zed\ProductCategory\Dependency\Facade;

interface ProductCategoryToCategoryInterface
{

    /**
     * @param string $categoryName
     * @param int $localeId
     *
     * @return bool
     */
    public function hasCategoryNode($categoryName, $localeId);

    /**
     * @param string $categoryName
     * @param int $localeId
     *
     * @return int
     */
    public function getCategoryNodeIdentifier($categoryName, $localeId);
}
