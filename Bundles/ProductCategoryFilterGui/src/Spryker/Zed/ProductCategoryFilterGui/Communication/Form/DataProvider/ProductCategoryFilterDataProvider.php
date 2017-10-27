<?php

namespace Spryker\Zed\ProductCategoryFilterGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ProductCategoryFilterTransfer;
use Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToProductCategoryFilterInterface;

class ProductCategoryFilterDataProvider
{
    /**
     * @var \Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToProductCategoryFilterInterface
     */
    protected $productCategoryFilterFacade;

    /**
     * @param \Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToProductCategoryFilterInterface $navigationFacade
     */
    public function __construct(ProductCategoryFilterGuiToProductCategoryFilterInterface $navigationFacade)
    {
        $this->productCategoryFilterFacade = $navigationFacade;
    }

    /**
     * @param int|null $idProductCategoryFilter
     *
     * @return array
     */
    public function getData($idProductCategoryFilter = null)
    {
        $productCategoryFilterTransfer = new ProductCategoryFilterTransfer();

        if ($idProductCategoryFilter) {
            $productCategoryFilterTransfer = $this->productCategoryFilterFacade->findProductCategoryFilterByCategoryId($idProductCategoryFilter);
        }

        return $productCategoryFilterTransfer->modifiedToArray();
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [];
    }
}
