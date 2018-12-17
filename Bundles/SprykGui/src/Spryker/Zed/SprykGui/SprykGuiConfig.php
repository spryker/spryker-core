<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui;

use Spryker\Shared\SprykGui\SprykGuiConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class SprykGuiConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isSprykAvailable(): bool
    {
        return $this->get(SprykGuiConstants::IS_AVAILABLE, false);
    }
}
