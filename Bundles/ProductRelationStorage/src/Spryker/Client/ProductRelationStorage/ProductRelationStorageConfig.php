<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductRelationStorage;

use Spryker\Client\Kernel\AbstractBundleConfig;

class ProductRelationStorageConfig extends AbstractBundleConfig
{
    /**
     * To be able to work with data exported with collectors to redis, we need to bring this module into compatibility
     * mode. If this is turned on the ProductRelationClient will be used instead.
     *
     * @api
     *
     * @return bool
     */
    public static function isCollectorCompatibilityMode(): bool
    {
        return false;
    }

    /**
     * Specification:
     * - Returns the maximum number of upselling products to be returned.
     *
     * @api
     *
     * @return int
     */
    public function getUpsellingProductLimit(): int
    {
        return 25;
    }
}
