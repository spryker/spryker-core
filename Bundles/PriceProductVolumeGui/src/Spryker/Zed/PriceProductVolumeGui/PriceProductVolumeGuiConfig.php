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

    protected const EMPTY_ROWS_QUANTITY = 3;
    protected const POW_BASE_VALUE = 10;
    protected const DEFAULT_SCALE = 2;
    protected const DEFAULT_DIVISOR = 1;

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

    /**
     * @return int
     */
    public function getEmptyRowsQuantity(): int
    {
        return static::EMPTY_ROWS_QUANTITY;
    }

    /**
     * @return int
     */
    public function getPowBaseValue(): int
    {
        return static::POW_BASE_VALUE;
    }

    /**
     * @return int
     */
    public function getDefaultScale(): int
    {
        return static::DEFAULT_SCALE;
    }

    /**
     * @return int
     */
    public function getDefaultDivisor(): int
    {
        return static::DEFAULT_DIVISOR;
    }
}
