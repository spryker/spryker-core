<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationCollector\Dependency\Facade;

class ProductRelationCollectorToPriceBridge implements ProductRelationCollectorToPriceInterface
{
    /**
     * @var \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @param \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface $priceFacade
     */
    public function __construct($priceFacade)
    {
        $this->priceProductFacade = $priceFacade;
    }

    /**
     * @param string $sku
     * @param string|null $priceTypeName
     *
     * @return int
     */
    public function getPriceBySku($sku, $priceTypeName = null)
    {
        return $this->priceProductFacade->getPriceBySku($sku, $priceTypeName);
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findPricesBySku($sku)
    {
        return $this->priceProductFacade->findPricesBySku($sku);
    }

    /**
     * @param string $sku
     *
     * @return mixed
     */
    public function findPricesBySkuGrouped($sku)
    {
        return $this->priceProductFacade->findPricesBySkuGrouped($sku);
    }
}
