<?php


namespace Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade;


interface ProductCategoryFilterGuiToProductSearchInterface
{
    /**
     * @param string $searchText
     * @param int $limit
     *
     * @return array
     */
    public function suggestProductSearchAttributeKeys($searchText = '', $limit = 10);
}