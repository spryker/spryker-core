<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantGuiConfig extends AbstractBundleConfig
{
    protected const SALUTATION_CHOICES = [
        'Ms' => 'Ms',
        'Mr' => 'Mr',
        'Mrs' => 'Mrs',
        'Dr' => 'Dr',
    ];

    /**
     * @return array
     */
    public function getSalutationChoices(): array
    {
        return static::SALUTATION_CHOICES;
    }
}
