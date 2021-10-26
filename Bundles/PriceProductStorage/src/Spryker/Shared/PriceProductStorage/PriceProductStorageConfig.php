<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PriceProductStorage;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class PriceProductStorageConfig extends AbstractSharedConfig
{
    /**
     * @see \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_DATA
     *
     * @var string
     */
    public const PRICE_DATA = 'priceData';

    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_DATA_BY_PRICE_TYPE
     *
     * @var string
     */
    public const PRICE_DATA_BY_PRICE_TYPE = 'priceDataByPriceType';

    /**
     * @see \Spryker\Shared\Price\PriceConfig::PRICE_MODE_NET
     *
     * @var string
     */
    public const PRICE_NET_MODE = 'NET_MODE';

    /**
     * @see \Spryker\Shared\Price\PriceConfig::PRICE_MODE_GROSS
     *
     * @var string
     */
    public const PRICE_GROSS_MODE = 'GROSS_MODE';

    /**
     * @see \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_MODES
     *
     * @var array
     */
    public const PRICE_MODES = [
        'NET_MODE',
        'GROSS_MODE',
    ];

    /**
     * Defines queue name for publish.
     *
     * @var string
     */
    public const PUBLISH_PRICE_PRODUCT_ABSTRACT = 'publish.price_product_abstract';

    /**
     * Defines queue name for publish.
     *
     * @var string
     */
    public const PUBLISH_PRICE_PRODUCT_CONCRETE = 'publish.price_product_concrete';
}
