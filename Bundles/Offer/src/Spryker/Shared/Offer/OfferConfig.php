<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Offer;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class OfferConfig extends AbstractBundleConfig
{
    public const STATUS_PENDING = 'Pending';
    public const STATUS_ORDER = 'order';
    public const STATUS_ON_OVERVIEW = 'On overview';
    public const STATUS_SENT_TO_CUSTOMER = 'Sent to customer';
    public const STATUS_CONFIRMED_BY_CUSTOMER = 'Confirmed by customer';
    public const STATUS_CLOSE = 'Close';
    
    /**
     * @return string
     */
    public function getStatusPending(): string
    {
        return static::STATUS_PENDING;
    }

    /**
     * @return string
     */
    public function getStatusOrder(): string
    {
        return static::STATUS_ORDER;
    }
}
