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
    public const STATUS_IN_PROGRESS = 'in_progress';

    /**
     * @uses \Spryker\Zed\Offer\OfferConfig
     */
    public const STATUS_ORDER = 'order';

    /**
     * @return string
     */
    public function getStatusInProgress(): string
    {
        return static::STATUS_IN_PROGRESS;
    }

    /**
     * @return string
     */
    public function getStatusOrder(): string
    {
        return static::STATUS_ORDER;
    }
}
