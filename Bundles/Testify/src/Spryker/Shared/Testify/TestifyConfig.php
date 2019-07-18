<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Testify;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class TestifyConfig extends AbstractSharedConfig
{
    /**
     * @return bool
     */
    public function isLocatorInstanceCacheEnabled(): bool
    {
        return false;
    }
}
