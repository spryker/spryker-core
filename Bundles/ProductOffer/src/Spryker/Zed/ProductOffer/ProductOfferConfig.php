<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductOfferConfig extends AbstractBundleConfig
{
    protected const DEFAULT_APPROVAL_STATUS = 'approved';

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultApprovalStatus(): string
    {
        return static::DEFAULT_APPROVAL_STATUS;
    }
}
