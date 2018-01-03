<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductStorage\Dependency\Facade;

use Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface;

class PriceProductStorageToPriceProductFacadeBridge
{

    /**
     * @var PriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @param PriceProductFacadeInterface $priceProductFacade
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
