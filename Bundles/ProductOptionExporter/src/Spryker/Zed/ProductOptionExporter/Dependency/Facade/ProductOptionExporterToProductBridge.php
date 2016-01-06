<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionExporter\Dependency\Facade;

class ProductOptionExporterToProductBridge implements ProductOptionExporterToProductInterface
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
     * @return int
     */
    public function getConcreteProductIdBySku($sku)
    {
        return $this->productFacade->getConcreteProductIdBySku($sku);
    }

    /**
     * @param string $sku
     *
     * @return float
     */
    public function getEffectiveTaxRateForConcreteProduct($sku)
    {
        return $this->productFacade->getEffectiveTaxRateForConcreteProduct($sku);
    }

}
