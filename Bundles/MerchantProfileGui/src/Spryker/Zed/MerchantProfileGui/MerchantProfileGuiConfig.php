<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantProfileGuiConfig extends AbstractBundleConfig
{
    public const COL_IS_ACTIVE = 'is_active';

    protected const PREFIX_MERCHANT_PROFILE_URL = 'merchant';

    protected const SALUTATION_CHOICES = [
        'Ms' => 'Ms',
        'Mr' => 'Mr',
        'Mrs' => 'Mrs',
        'Dr' => 'Dr',
    ];

    /**
     * @api
     *
     * @return array
     */
    public function getSalutationChoices(): array
    {
        return static::SALUTATION_CHOICES;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getIsActiveColumnName(): string
    {
        return static::COL_IS_ACTIVE;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getMerchantUrlPrefix(): string
    {
        return static::PREFIX_MERCHANT_PROFILE_URL;
    }
}
