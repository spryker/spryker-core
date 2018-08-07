<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCart;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\SharedCart\SharedCartConfig as SharedCartConfigConstants;

class SharedCartConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getOwnerPermission(): string
    {
        return SharedCartConfigConstants::PERMISSION_GROUP_OWNER_ACCESS;
    }

    /**
     * @return string
     */
    public function getFullPermission(): string
    {
        return SharedCartConfigConstants::PERMISSION_GROUP_FULL_ACCESS;
    }

    /**
     * @return string
     */
    public function getReadPermission(): string
    {
        return SharedCartConfigConstants::PERMISSION_GROUP_READ_ONLY;
    }
}
