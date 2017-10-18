<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Price;

use Spryker\Shared\Config\Config;

class PriceConfig
{
    const PRICE_MODE_NET = 'NET_MODE';
    const PRICE_MODE_GROSS = 'GROSS_MODE';


    /**
     * @return string[]
     */
    public function getPriceModes()
    {
        return [
            static::PRICE_MODE_NET => static::PRICE_MODE_NET,
            static::PRICE_MODE_GROSS => static::PRICE_MODE_GROSS,
        ];
    }

    /**
     * @return string
     */
    public function getDefaultPriceMode()
    {
        return Config::get(PriceConstants::DEFAULT_PRICE_MODE, static::PRICE_MODE_GROSS);
    }

    /**
     * @return string
     */
    public function getNetPriceModeIdentifier()
    {
        return static::PRICE_MODE_NET;
    }

    /**
     * @return string
     */
    public function getGrossPriceModeIdentifier()
    {
        return static::PRICE_MODE_GROSS;
    }
}
