<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscountConnector\Dependency\Facade;

use Spryker\Zed\Product\Business\ProductFacade;

class ProductDiscountConnectorToProductBridge implements ProductDiscountConnectorToProductInterface
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
     * @param string $abstractSku
     * @return
     */
    public function getProductVariantsByAbstractSku($abstractSku)
    {
        return $this->productFacade->getProductVariantsByAbstractSku($abstractSku);
    }
}
