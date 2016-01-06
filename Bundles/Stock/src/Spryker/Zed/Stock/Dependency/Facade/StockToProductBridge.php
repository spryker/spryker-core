<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Stock\Dependency\Facade;

use Spryker\Zed\Product\Business\Exception\MissingProductException;

class StockToProductBridge implements StockToProductInterface
{

    /**
     * @var \Spryker\Zed\Product\Business\ProductFacade
     */
    protected $productFacade;

    /**
     * ProductCategoryToProductBridge constructor.
     *
     * @param \Spryker\Zed\Product\Business\ProductFacade $productFacade
     */
    public function __construct($productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param string $sku
     *
     * @throws MissingProductException
     *
     * @return int
     */
    public function getAbstractProductIdBySku($sku)
    {
        return $this->productFacade->getAbstractProductIdBySku($sku);
    }

    /**
     * @param string $sku
     *
     * @throws MissingProductException
     *
     * @return int
     */
    public function getConcreteProductIdBySku($sku)
    {
        return $this->productFacade->getConcreteProductIdBySku($sku);
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasConcreteProduct($sku)
    {
        return $this->productFacade->hasConcreteProduct($sku);
    }

}
