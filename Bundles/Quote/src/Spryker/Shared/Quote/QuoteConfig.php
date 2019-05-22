<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Quote;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class QuoteConfig extends AbstractSharedConfig
{
    public const STORAGE_STRATEGY_SESSION = 'session';
    public const STORAGE_STRATEGY_DATABASE = 'database';

    /**
     * @return string
     */
    public function getStorageStrategy()
    {
        return static::STORAGE_STRATEGY_SESSION;
    }
}
