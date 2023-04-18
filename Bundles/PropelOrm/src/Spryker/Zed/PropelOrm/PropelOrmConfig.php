<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelOrm;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class PropelOrmConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Defines if boolean casting is enabled for data formatting.
     *
     * @api
     *
     * @return bool
     */
    public function isBooleanCastingEnabled(): bool
    {
        return false;
    }
}
