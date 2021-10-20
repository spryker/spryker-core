<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOption;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantProductOptionConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const STATUS_WAITING_FOR_APPROVAL = 'waiting_for_approval';

    /**
     * @var string
     */
    public const STATUS_APPROVED = 'approved';

    /**
     * @var string
     */
    public const STATUS_DENIED = 'denied';
}
