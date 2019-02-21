<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantConfig extends AbstractBundleConfig
{
    public const STATUS_WAITING_FOR_APPROVAL = 'waiting-for-approval';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    /**
     * @return string
     */
    public function getDefaultMerchantStatus(): string
    {
        return static::STATUS_WAITING_FOR_APPROVAL;
    }

    /**
     * @return array
     */
    public function getStatusTree(): array
    {
        return [
            static::STATUS_WAITING_FOR_APPROVAL => [
                static::STATUS_APPROVED,
            ],
            static::STATUS_APPROVED => [
                static::STATUS_ACTIVE,
                static::STATUS_INACTIVE,
            ],
            static::STATUS_ACTIVE => [
                static::STATUS_INACTIVE,
            ],
            static::STATUS_INACTIVE => [
                static::STATUS_ACTIVE,
            ],
        ];
    }
}
