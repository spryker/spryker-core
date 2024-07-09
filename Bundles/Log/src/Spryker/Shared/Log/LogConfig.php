<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Log;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class LogConfig extends AbstractSharedConfig
{
    /**
     * Specification:
     * - Provides an audit logger channel name used for security logs.
     *
     * @api
     *
     * @var string
     */
    public const AUDIT_LOGGER_CHANNEL_NAME_SECURITY = 'security';
}
