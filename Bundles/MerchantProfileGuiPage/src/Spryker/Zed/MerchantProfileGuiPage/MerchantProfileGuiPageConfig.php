<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGuiPage;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantProfileGuiPageConfig extends AbstractBundleConfig
{
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
     * @return string[]
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
    public function getMerchantUrlPrefix(): string
    {
        return static::PREFIX_MERCHANT_PROFILE_URL;
    }
}
