<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const STATUS_WAITING_FOR_APPROVAL = 'waiting-for-approval';

    /**
     * @var string
     */
    public const STATUS_APPROVED = 'approved';

    /**
     * @var string
     */
    public const STATUS_DENIED = 'denied';

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultMerchantStatus(): string
    {
        return static::STATUS_WAITING_FOR_APPROVAL;
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
