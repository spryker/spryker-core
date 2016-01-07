<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\TaxProductConnector\Dependency\Facade;

use Spryker\Zed\Product\Business\ProductFacade;

class TaxProductConnectorToProductBridge implements TaxProductConnectorToProductInterface
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
     * @param int $idAbstractProduct
     */
    public function touchProductActive($idAbstractProduct)
    {
        $this->productFacade->touchProductActive($idAbstractProduct);
    }

}
