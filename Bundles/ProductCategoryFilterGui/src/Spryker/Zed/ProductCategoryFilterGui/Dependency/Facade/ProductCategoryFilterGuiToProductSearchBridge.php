<?php

namespace Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade;

class ProductCategoryFilterGuiToProductSearchBridge implements ProductCategoryFilterGuiToProductSearchInterface
{
    /**
     * @var \Spryker\Zed\ProductSearch\Business\ProductSearchFacadeInterface
     */
    protected $productSearchFacade;

    /**
     * @param \Spryker\Zed\ProductSearch\Business\ProductSearchFacadeInterface $productSearchFacade
     */
    public function __construct($productSearchFacade)
    {
        $this->productSearchFacade = $productSearchFacade;
    }

    /**
     * @param string $searchText
     * @param int $limit
     *
     * @return array
     */
    public function suggestProductSearchAttributeKeys($searchText = '', $limit = 10)
    {
        return $this->productSearchFacade->suggestProductSearchAttributeKeys($searchText, $limit);
    }
}
