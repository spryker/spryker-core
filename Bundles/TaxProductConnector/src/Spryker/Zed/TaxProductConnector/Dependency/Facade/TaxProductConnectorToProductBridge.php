<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\TaxProductConnector\Dependency\Facade;

class TaxProductConnectorToProductBridge implements TaxProductConnectorToProductInterface
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
     * @param int $idAbstractProduct
     */
    public function touchProductActive($idAbstractProduct)
    {
        $this->productFacade->touchProductActive($idAbstractProduct);
    }

}
