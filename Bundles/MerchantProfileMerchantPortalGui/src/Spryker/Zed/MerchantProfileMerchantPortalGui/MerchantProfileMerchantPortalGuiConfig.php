<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileMerchantPortalGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantProfileMerchantPortalGuiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const PREFIX_MERCHANT_PROFILE_URL = 'merchant';

    /**
     * @var array
     */
    protected const SALUTATION_CHOICES = [
        'Ms' => 'Ms',
        'Mr' => 'Mr',
        'Mrs' => 'Mrs',
        'Dr' => 'Dr',
    ];

    /**
     * @api
     *
     * @return array<string>
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

    /**
     * @api
     *
     * @return array<string>
     */
    public function getHtmlTagWhitelist(): array
    {
        return [
            '<h1>',
            '<h2>',
            '<h3>',
            '<h4>',
            '<h5>',
            '<h6>',
            '<br>',
            '<p>',
        ];
    }
}
