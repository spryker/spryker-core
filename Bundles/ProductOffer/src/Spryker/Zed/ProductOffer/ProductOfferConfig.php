<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductOfferConfig extends AbstractBundleConfig
{
    protected const STATUS_WAITING_FOR_APPROVAL = 'waiting-for-approval';
    protected const STATUS_APPROVED = 'approved';
    protected const STATUS_DENIED = 'denied';

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultApprovalStatus(): string
    {
        return static::STATUS_APPROVED;
    }

    /**
     * @api
     *
     * @return array
     */
    public function getStatusTree(): array
    {
        return [
            static::STATUS_WAITING_FOR_APPROVAL => [
                static::STATUS_APPROVED,
                static::STATUS_DENIED,
            ],
            static::STATUS_APPROVED => [
                static::STATUS_DENIED,
            ],
            static::STATUS_DENIED => [
                static::STATUS_APPROVED,
            ],
        ];
    }
}
