<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantConfig extends AbstractBundleConfig
{
    protected const MERCHANT_STATUS_WAITING_FOR_APPROVAL = 'waiting-for-approval';

    /**
     * @return string
     */
    public function getDefaultMerchantStatus(): string
    {
        return static::MERCHANT_STATUS_WAITING_FOR_APPROVAL;
    }
}
