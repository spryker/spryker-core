<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PriceProductVolume;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class PriceProductVolumeConfig extends AbstractSharedConfig
{
    public const VOLUME_PRICE_TYPE = 'volume_prices';
    public const VOLUME_PRICE_QUANTITY = 'quantity';
    public const VOLUME_PRICE_NET_PRICE = 'net_price';
    public const VOLUME_PRICE_GROSS_PRICE = 'gross_price';
}
