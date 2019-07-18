<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Session;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class SessionConfig extends AbstractSharedConfig
{
    public const SESSION_HANDLER_COUCHBASE = 'couchbase';

    /**
     * @deprecated Use `Spryker\Shared\SessionRedis\SessionRedisConfig::SESSION_HANDLER_REDIS` instead.
     */
    public const SESSION_HANDLER_REDIS = 'redis';

    /**
     * @deprecated Use `Spryker\Shared\SessionRedis\SessionRedisConfig::SESSION_HANDLER_REDIS_LOCKING` instead.
     */
    public const SESSION_HANDLER_REDIS_LOCKING = 'redis_locking';
    public const SESSION_HANDLER_MYSQL = 'mysql';

    /**
     * @deprecated Use `Spryker\Shared\SessionFile\SessionFileConfig::SESSION_HANDLER_FILE` instead.
     */
    public const SESSION_HANDLER_FILE = 'file';

    public const SESSION_LIFETIME_BROWSER_SESSION = '0';
    public const SESSION_LIFETIME_1_HOUR = '3600';
    public const SESSION_LIFETIME_0_5_HOUR = '1800';
    public const SESSION_LIFETIME_1_DAY = '86400';
    public const SESSION_LIFETIME_2_DAYS = '172800';
    public const SESSION_LIFETIME_3_DAYS = '259200';
    public const SESSION_LIFETIME_30_DAYS = '2592000';
    public const SESSION_LIFETIME_1_YEAR = '31536000';
}
