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
    /**
     * @return string
     */
    public function getInitialStatus(): string
    {
        return SharedOfferConfig::STATUS_PENDING;
    }

    /**
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
}
