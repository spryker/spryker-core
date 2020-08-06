<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer;

use Spryker\Shared\Offer\OfferConfig as SharedOfferConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\Offer\OfferConfig getSharedConfig()
 */
class OfferConfig extends AbstractBundleConfig
{
    public const PRICE_MODE_NET = 'NET_MODE';

    /**
     * @api
     *
     * @return string
     */
    public function getStatusPending(): string
    {
        return $this->getSharedConfig()->getStatusPending();
    }

    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_GROSS
     */
    public const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @api
     *
     * @return string
     */
    public function getInitialStatus(): string
    {
        return SharedOfferConfig::STATUS_PENDING;
    }

    /**
     * @api
     *
     * @return array
     */
    public function getIncompleteOfferStatuses(): array
    {
        return [
            SharedOfferConfig::STATUS_PENDING,
            SharedOfferConfig::STATUS_ON_OVERVIEW,
            SharedOfferConfig::STATUS_CONFIRMED_BY_CUSTOMER,
            SharedOfferConfig::STATUS_SENT_TO_CUSTOMER,
        ];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getConvertedStatus(): string
    {
        return SharedOfferConfig::STATUS_CLOSE;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getPriceModeNet()
    {
        return static::PRICE_MODE_NET;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getPriceModeGross()
    {
        return static::PRICE_MODE_GROSS;
    }
}
