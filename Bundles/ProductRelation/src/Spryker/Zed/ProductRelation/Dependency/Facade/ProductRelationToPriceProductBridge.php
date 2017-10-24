<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Dependency\Facade;

class ProductRelationToPriceProductBridge implements ProductRelationToPriceProductInterface
{
    /**
     * @var \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
     protected $priceProductFacade;

    /**
     * @param \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface $priceProduct
     */
    public function __construct($priceProduct)
    {
        $this->priceProductFacade = $priceProduct;
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    public function getPriceBySku($sku)
    {
        return $this->priceProductFacade->getPriceBySku($sku);
    }
}
