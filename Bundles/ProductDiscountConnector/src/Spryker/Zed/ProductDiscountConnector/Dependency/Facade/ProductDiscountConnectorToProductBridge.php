<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscountConnector\Dependency\Facade;

class ProductDiscountConnectorToProductBridge implements ProductDiscountConnectorToProductInterface
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
     * @param string $abstractSku
     *
     * @return \Generated\Shared\Transfer\ProductVariantTransfer[]
     */
    public function getProductVariantsByAbstractSku($abstractSku)
    {
        return $this->productFacade->getProductVariantsByAbstractSku($abstractSku);
    }

}
