<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StoreContextStorage;

use Spryker\Client\Kernel\AbstractBundleConfig;

class StoreContextStorageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Returns the application name.
     *
     * @api
     *
     * @return string
     */
    public function getApplicationName(): string
    {
        return APPLICATION;
    }
}
