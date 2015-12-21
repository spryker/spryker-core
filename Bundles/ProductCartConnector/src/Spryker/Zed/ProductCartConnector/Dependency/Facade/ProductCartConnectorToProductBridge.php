<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCartConnector\Dependency\Facade;

use Generated\Shared\Transfer\ConcreteProductTransfer;

class ProductCartConnectorToProductBridge implements ProductCartConnectorToProductInterface
{

    /**
     * @var \Spryker\Zed\Product\Business\ProductFacade
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\Product\Business\ProductFacade $productFacade
     */
    public function __construct($productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param string $concreteSku
     *
     * @return ConcreteProductTransfer
     */
    public function getConcreteProduct($concreteSku)
    {
        return $this->productFacade->getConcreteProduct($concreteSku);
    }

}
