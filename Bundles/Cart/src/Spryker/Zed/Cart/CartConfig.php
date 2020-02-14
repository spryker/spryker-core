<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CartConfig extends AbstractBundleConfig
{
    public const OPERATION_ADD = 'add';

    public const OPERATION_REMOVE = 'remove';

    protected const DEFAULT_OPERATION_PLUGINS_EXECUTOR_BATCH_SIZE = 1000;

    /**
     * @return int
     */
    protected function getOperationPluginsExecutorButchSize(): int
    {
        return static::DEFAULT_OPERATION_PLUGINS_EXECUTOR_BATCH_SIZE;
    }
}
