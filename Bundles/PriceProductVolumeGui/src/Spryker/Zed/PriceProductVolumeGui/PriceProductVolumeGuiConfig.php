<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolumeGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class PriceProductVolumeGuiConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Shared\PriceProductStorage\PriceProductStorageConstants::PRICE_DIMENSION_DEFAULT
     */
    protected const PRICE_DIMENSION_DEFAULT = 'PRICE_DIMENSION_DEFAULT';

    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_TYPE_DEFAULT
     */
    protected const PRICE_TYPE_DEFAULT = 'DEFAULT';

    /**
     * @uses \Spryker\Shared\PriceProductVolume\VolumePriceProductConfig::VOLUME_PRICE_TYPE
     */
    protected const VOLUME_PRICE_TYPE = 'volume_prices';

    /**
     * @return string
     */
    public function getPriceDimensionDefaultName(): string
    {
        return static::PRICE_DIMENSION_DEFAULT;
    }

    /**
     * @return string
     */
    public function getPriceTypeDefaultName(): string
    {
        return static::PRICE_TYPE_DEFAULT;
    }

    /**
     * @return string
     */
    public function getVolumePriceTypeName(): string
    {
        return static::VOLUME_PRICE_TYPE;
    }
}
