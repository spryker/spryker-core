<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShipmentTypeStorage;

use Spryker\Client\Kernel\AbstractBundleConfig;

class ShipmentTypeStorageConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const SCAN_KEY_STORE_LIMIT = 10;

    /**
     * Specification:
     *  - Restricts the number of scan keys due to performance reasons.
     *
     * @api
     *
     * @deprecated Exists for BC reasons. Will be removed in the next major release.
     *
     * @return int
     */
    public function getScanKeyStoreLimit(): int
    {
        return static::SCAN_KEY_STORE_LIMIT;
    }
}
