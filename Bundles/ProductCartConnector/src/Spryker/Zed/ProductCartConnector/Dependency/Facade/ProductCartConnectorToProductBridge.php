<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCartConnector\Dependency\Facade;

use Spryker\Zed\Product\Business\ProductFacade;
use Generated\Shared\Transfer\ProductConcreteTransfer;

class ProductCartConnectorToProductBridge implements ProductCartConnectorToProductInterface
{

    /**
     * @var ProductFacade
     */
    protected $productFacade;

    /**
     * @param ProductFacade $productFacade
     */
    public function __construct($productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param string $concreteSku
     *
     * @return ProductConcreteTransfer
     */
    public function getProductConcrete($concreteSku)
    {
        return $this->productFacade->getProductConcrete($concreteSku);
    }

}
