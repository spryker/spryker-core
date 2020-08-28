<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferGui\Dependency\Facade;

class PriceProductOfferGuiToPriceFacadeBridge implements PriceProductOfferGuiToPriceFacadeInterface
{
    /**
     * @var \Spryker\Zed\Price\Business\PriceFacadeInterface
     */
    protected $priceFacade;

    /**
     * @param \Spryker\Zed\Price\Business\PriceFacadeInterface $priceFacade
     */
    public function __construct($priceFacade)
    {
        $this->priceFacade = $priceFacade;
    }

    /**
     * @return string
     */
    public function getNetPriceModeIdentifier()
    {
        return $this->priceFacade->getNetPriceModeIdentifier();
    }

    /**
     * @return string
     */
    public function getGrossPriceModeIdentifier()
    {
        return $this->priceFacade->getGrossPriceModeIdentifier();
    }
}
