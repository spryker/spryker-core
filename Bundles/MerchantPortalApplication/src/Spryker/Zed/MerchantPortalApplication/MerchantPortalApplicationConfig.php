<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantPortalApplication;

use Spryker\Shared\MerchantPortalApplication\MerchantPortalConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\MerchantPortalApplication\MerchantPortalApplicationConfig getSharedConfig()
 */
class MerchantPortalApplicationConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return bool
     */
    public function isDebugModeEnabled(): bool
    {
        return (bool)$this->get(MerchantPortalConstants::ENABLE_APPLICATION_DEBUG, false);
    }
}
