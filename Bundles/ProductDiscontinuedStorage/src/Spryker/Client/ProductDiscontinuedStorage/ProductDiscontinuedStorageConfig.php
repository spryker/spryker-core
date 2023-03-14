<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductDiscontinuedStorage;

use Spryker\Client\Kernel\AbstractBundleConfig;

class ProductDiscontinuedStorageConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Enables discontinued selected attributes postfix for only discontinued product variant.
     *
     * @api
     *
     * @return bool
     */
    public function isOnlyDiscontinuedVariantAttributesPostfixEnabled(): bool
    {
        return false;
    }
}
