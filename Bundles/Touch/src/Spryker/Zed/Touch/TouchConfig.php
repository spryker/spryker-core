<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Touch;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class TouchConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return bool
     */
    public function isTouchEnabled(): bool
    {
        return true;
    }
}
