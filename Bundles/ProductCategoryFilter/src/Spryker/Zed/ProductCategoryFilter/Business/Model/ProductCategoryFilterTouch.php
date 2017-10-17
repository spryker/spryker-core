<?php

namespace Spryker\Zed\ProductCategoryFilter\Business\Model;

use Generated\Shared\Transfer\ProductCategoryFilterTransfer;
use Spryker\Shared\ProductCategoryFilter\ProductCategoryFilterConfig;
use Spryker\Zed\ProductCategoryFilter\Dependency\Facade\ProductCategoryFilterToTouchInterface;
use Spryker\Zed\ProductCategoryFilter\Persistence\ProductCategoryFilterQueryContainerInterface;

class ProductCategoryFilterTouch implements ProductCategoryFilterTouchInterface
{
    /**
     * @var ProductCategoryFilterToTouchInterface
     */
    protected $touchFacade;

    /**
     * ProductCategoryFilterTouch constructor.
     * @param ProductCategoryFilterToTouchInterface $touchFacade
     * @param ProductCategoryFilterQueryContainerInterface $productGroupQueryContainer
     */
    public function __construct(ProductCategoryFilterToTouchInterface $touchFacade, ProductCategoryFilterQueryContainerInterface $productGroupQueryContainer)
    {
        $this->touchFacade = $touchFacade;
        $this->productGroupQueryContainer = $productGroupQueryContainer;
    }

    /**
     * @param ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return bool
     */
    public function touchProductCategoryFilterActive(ProductCategoryFilterTransfer $productCategoryFilterTransfer)
    {
        return $this->touchFacade->touchActive(ProductCategoryFilterConfig::RESOURCE_TYPE_PRODUCT_CATEGORY_FILTER, $productCategoryFilterTransfer->getIdProductCategoryFilter());
    }

    /**
     * @param ProductCategoryFilterTransfer $productCategoryFilterTransfer
     *
     * @return bool
     */
    public function touchProductCategoryFilterDeleted(ProductCategoryFilterTransfer $productCategoryFilterTransfer)
    {
        return $this->touchFacade->touchDeleted(ProductCategoryFilterConfig::RESOURCE_TYPE_PRODUCT_CATEGORY_FILTER, $productCategoryFilterTransfer->getIdProductCategoryFilter());
    }
}
