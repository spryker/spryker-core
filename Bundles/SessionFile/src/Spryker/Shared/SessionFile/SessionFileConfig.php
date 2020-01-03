<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionFile;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class SessionFileConfig extends AbstractBundleConfig
{
    public const SESSION_HANDLER_FILE = 'file';

    /**
     * @return string
     */
    public function getSessionHandlerFileName(): string
    {
        return static::SESSION_HANDLER_FILE;
    }
}
