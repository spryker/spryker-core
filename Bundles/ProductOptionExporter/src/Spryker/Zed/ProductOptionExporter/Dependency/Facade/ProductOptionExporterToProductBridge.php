<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionExporter\Dependency\Facade;

use Spryker\Zed\Product\Business\ProductFacade;

class ProductOptionExporterToProductBridge implements ProductOptionExporterToProductInterface
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
