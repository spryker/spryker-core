<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class OfferConfig extends AbstractBundleConfig
{
    public const ORDER_TYPE_OFFER = 'offer';

    /**
     * @use \Spryker\Zed\Sales\SalesConfig::getOrderTypeDefault()
     */
    public const ORDER_TYPE_DEFAULT = null;

    /**
     * @return string
     */
    public function getOrderTypeOffer(): string
    {
        return static::ORDER_TYPE_OFFER;
    }

    /**
     * @see \Spryker\Zed\Sales\SalesConfig::getOrderTypeDefault()
     *
     * @return null|string
     */
    public function getOrderTypeDefault(): ?string
    {
       return static::ORDER_TYPE_DEFAULT;
    }
}
