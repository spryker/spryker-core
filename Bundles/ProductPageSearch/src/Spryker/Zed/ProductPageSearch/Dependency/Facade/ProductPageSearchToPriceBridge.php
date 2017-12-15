<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Dependency\Facade;

class ProductPageSearchToPriceBridge implements ProductPageSearchToPriceInterface
{

    /**
     * @var \Spryker\Zed\Price\Business\PriceFacadeInterface
     */
    protected $priceFacade;

    /**
     * ProductPageSearchToPriceBridge constructor.
     *
     * @param \Spryker\Zed\Price\Business\PriceFacadeInterface $priceFacade
     */
    public function __construct($priceFacade)
    {
        $this->priceFacade = $priceFacade;
    }

    /**
     * @param string $sku
     * @param string|null $priceTypeName
     *
     * @return int
     */
    public function getPriceBySku($sku, $priceTypeName = null)
    {
        return $this->priceFacade->getPriceBySku($sku, $priceTypeName);
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findPricesBySku($sku)
    {
        return $this->priceFacade->findPricesBySku($sku);
    }

}
