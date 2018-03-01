<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductStorage\Dependency\Facade;

class PriceProductStorageToPriceProductFacadeBridge implements PriceProductStorageToPriceProductFacadeInterface
{
    /**
     * @var \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @param \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface $priceProductFacade
     */
    public function __construct($priceProductFacade)
    {
        $this->priceProductFacade = $priceProductFacade;
    }

    /**
     * @param string $sku
     *
     * @return array
     */
    public function findPricesBySkuGroupedForCurrentStore($sku)
    {
        return $this->priceProductFacade->findPricesBySkuGroupedForCurrentStore($sku);
    }
}
