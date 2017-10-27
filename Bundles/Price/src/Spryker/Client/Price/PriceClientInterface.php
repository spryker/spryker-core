<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Price;

/**
 * @method \Spryker\Client\Price\PriceFactory getFactory()
 * @method \Spryker\Client\Price\PriceConfig getConfig()
 */
interface PriceClientInterface
{
    /**
     * @api
     *
     * @return string
     */
    public function getCurrentPriceMode();

    /**
     * @api
     *
     * @return string
     */
    public function getGrossPriceModeIdentifier();

    /**
     * @api
     *
     * @return string
     */
    public function getNetPriceModeIdentifier();
}
