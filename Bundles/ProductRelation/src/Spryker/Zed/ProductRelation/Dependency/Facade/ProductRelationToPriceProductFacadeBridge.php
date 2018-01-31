<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Dependency\Facade;

class ProductRelationToPriceProductFacadeBridge implements ProductRelationToPriceProductFacadeInterface
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
     * @return int|null
     */
    public function findPriceBySku($sku)
    {
        return $this->priceProductFacade->findPriceBySku($sku);
    }
}
