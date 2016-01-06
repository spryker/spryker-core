<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCategory\Dependency\Facade;

use Spryker\Zed\Product\Business\ProductFacade;

class ProductCategoryToProductBridge implements ProductCategoryToProductInterface
{

    /**
     * @var ProductFacade
     */
    protected $productFacade;

    /**
     * ProductCategoryToProductBridge constructor.
     *
     * @param ProductFacade $productFacade
     */
    public function __construct($productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasAbstractProduct($sku)
    {
        return $this->productFacade->hasAbstractProduct($sku);
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    public function getAbstractProductIdBySku($sku)
    {
        return $this->productFacade->getAbstractProductIdBySku($sku);
    }

}
