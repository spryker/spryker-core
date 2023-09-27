<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKeyAuthorizationConnector;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class ApiKeyAuthorizationConnectorConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const LOG_FILE_PATH = '%s/data/api-key-authorization/logs/%s.log';

    /**
     * Specification:
     * - Returns absolute file path for dynamic entities logs.
     *
     * @api
     *
     * @return string
     */
    public function getLogFilepath(): string
    {
        return sprintf(static::LOG_FILE_PATH, APPLICATION_ROOT_DIR, date('Y-m-d'));
    }

    /**
     * Specification:
     * - Defines whether logging for dynamic entities is enabled.
     *
     * @api
     *
     * @return bool
     */
    public function isLoggingEnabled(): bool
    {
        return true;
    }
}
