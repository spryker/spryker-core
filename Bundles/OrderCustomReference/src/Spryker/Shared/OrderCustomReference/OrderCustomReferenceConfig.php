<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\OrderCustomReference;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class OrderCustomReferenceConfig extends AbstractSharedConfig
{
    protected const ORDER_CUSTOM_REFERENCE_MAX_LENGTH = 255;

    /**
     * Specification:
     * - Returns the maximum permissible length of the order custom reference value.
     *
     * @api
     *
     * @return int
     */
    public function getOrderCustomReferenceMaxLength(): int
    {
        return static::ORDER_CUSTOM_REFERENCE_MAX_LENGTH;
    }
}
