<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PriceProductOfferVolume;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class PriceProductOfferVolumeConfig extends AbstractSharedConfig
{
    /**
     * Specification:
     *  - Dimension type as used for product offer volume price.
     *
     * @var string
     */
    public const DIMENSION_TYPE_PRICE_PRODUCT_OFFER_VOLUME = 'PRICE_PRODUCT_OFFER_VOLUME';

    /**
     * @var string
     */
    public const VOLUME_PRICE_TYPE = 'volume_prices';

    /**
     * @var string
     */
    public const VOLUME_PRICE_QUANTITY = 'quantity';

    /**
     * @var string
     */
    public const VOLUME_PRICE_NET_PRICE = 'net_price';

    /**
     * @var string
     */
    public const VOLUME_PRICE_GROSS_PRICE = 'gross_price';
}
