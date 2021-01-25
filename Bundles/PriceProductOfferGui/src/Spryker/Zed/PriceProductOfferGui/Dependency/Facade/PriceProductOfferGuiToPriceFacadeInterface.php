<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferGui\Dependency\Facade;

interface PriceProductOfferGuiToPriceFacadeInterface
{
    /**
     * @return string
     */
    public function getNetPriceModeIdentifier();

    /**
     * @return string
     */
    public function getGrossPriceModeIdentifier();
}
