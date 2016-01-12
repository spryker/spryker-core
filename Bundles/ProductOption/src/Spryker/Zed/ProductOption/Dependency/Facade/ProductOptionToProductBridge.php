<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOption\Dependency\Facade;

use Spryker\Zed\Product\Business\ProductFacade;
use Spryker\Zed\Product\Business\Exception\MissingProductException;

class ProductOptionToProductBridge implements ProductOptionToProductInterface
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
     * @throws MissingProductException
     *
     * @return int
     */
    public function getProductAbstractIdByConcreteSku($sku)
    {
        return $this->productFacade->getProductAbstractIdByConcreteSku($sku);
    }

    /**
     * @param int $idProductAbstract
     */
    public function touchProductActive($idProductAbstract)
    {
        $this->productFacade->touchProductActive($idProductAbstract);
    }

}
