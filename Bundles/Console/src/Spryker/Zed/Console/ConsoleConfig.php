<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Console;

use Spryker\Shared\Console\ConsoleConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ConsoleConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isCatchExceptions(): bool
    {
        return $this->get(ConsoleConstants::CONSOLE_CATCH_EXCEPTIONS, false);
    }
}
