<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StorageGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class StorageGuiConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const DEFAULT_PAGE_LENGTH = 100;

    /**
     * @api
     *
     * @return int
     */
    public function getGuiDefaultPageLength(): int
    {
        return static::DEFAULT_PAGE_LENGTH;
    }
}
