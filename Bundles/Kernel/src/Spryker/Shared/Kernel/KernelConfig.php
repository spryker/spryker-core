<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel;

class KernelConfig extends AbstractSharedConfig
{
    /**
     * Set this to true if you want to return already located instances instead of creating new ones for each call.
     *
     * @return bool
     */
    public function isLocatorInstanceCacheEnabled(): bool
    {
        return false;
    }
}
