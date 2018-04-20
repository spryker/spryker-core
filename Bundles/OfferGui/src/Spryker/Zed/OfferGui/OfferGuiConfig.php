<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class OfferGuiConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Shared\Offer\OfferConfig
     */
    public const STATUS_PENDING = 'Pending';

    /**
     * @uses \Spryker\Shared\Offer\OfferConfig
     */
    public const STATUS_ON_OVERVIEW = 'On overview';

    /**
     * @uses \Spryker\Shared\Offer\OfferConfig
     */
    public const STATUS_SENT_TO_CUSTOMER = 'Sent to customer';

    /**
     * @uses \Spryker\Shared\Offer\OfferConfig
     */
    public const STATUS_CONFIRMED_BY_CUSTOMER = 'Confirmed by customer';

    /**
     * @uses \Spryker\Shared\Offer\OfferConfig
     */
    public const STATUS_CLOSE = 'Close';

    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_NET
     */
    public const PRICE_MODE_NET = 'NET_MODE';

    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_GROSS
     */
    public const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @return string
     */
    public function getPriceModeNet()
    {
        return static::PRICE_MODE_NET;
    }

    /**
     * @return string
     */
    public function getPriceModeGross()
    {
        return static::PRICE_MODE_GROSS;
    }
}
