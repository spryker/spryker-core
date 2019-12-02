<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductOfferConfig extends AbstractBundleConfig
{
    public const STATUS_WAITING_FOR_APPROVAL = 'waiting_for_approval';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_DECLINED = 'declined';
}
