<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PriceProduct;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class PriceProductConfig extends AbstractSharedConfig
{
    /**
     * Price mode for price type when its applicable to gross and net price modes.
     */
    protected const PRICE_MODE_BOTH = 'BOTH';

    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_NET
     */
    protected const PRICE_NET_MODE = 'NET_MODE';

    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_GROSS
     */
    public const PRICE_GROSS_MODE = 'GROSS_MODE';

    /**
     * Price Dimension Default
     */
    public const PRICE_DIMENSION_DEFAULT = 'PRICE_DIMENSION_DEFAULT';

    /**
     * Price type default
     */
    protected const PRICE_TYPE_DEFAULT = 'DEFAULT';

    /**
     * Price data
     */
    public const PRICE_DATA = 'priceData';

    /**
     * List of price modes
     */
    public const PRICE_MODES = [
        'NET_MODE',
        'GROSS_MODE',
    ];

    /**
     * Price dimension name default
     */
    protected const PRICE_DIMENSION_DEFAULT_NAME = 'Default';

    /**
     * Decides if orphan prices need to be cleared after every product price update.
     */
    protected const DELETE_ORPHAN_PRICES_MODE_ENABLED = true;

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
    public function getPriceDimensionDefault(): string
    {
        return static::PRICE_DIMENSION_DEFAULT;
    }

    /**
     * @return string
     */
    public function getPriceModeIdentifierForBothType(): string
    {
        return static::PRICE_MODE_BOTH;
    }

    /**
     * @return string
     */
    public function getPriceModeIdentifierForNetType(): string
    {
        return static::PRICE_NET_MODE;
    }

    /**
     * @return string
     */
    public function getPriceModeIdentifierForGrossType(): string
    {
        return static::PRICE_GROSS_MODE;
    }

    /**
     * @return string
     */
    public function getPriceDimensionDefaultName(): string
    {
        return static::PRICE_DIMENSION_DEFAULT_NAME;
    }

    /**
     * @return bool
     */
    public function getDeleteOrphanPricesModeEnabled(): bool
    {
        return static::DELETE_ORPHAN_PRICES_MODE_ENABLED;
    }
}
