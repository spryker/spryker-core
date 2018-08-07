<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCart;

use Spryker\Client\Kernel\AbstractBundleConfig;

class SharedCartConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getOwnerPermission(): string
    {
        return 'owner';
    }

    /**
     * @return string
     */
    public function getFullPermission(): string
    {
        return 'full';
    }

    /**
     * @return string
     */
    public function getReadPermission(): string
    {
        return 'read';
    }
}
