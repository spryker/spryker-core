<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Session;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface SessionConstants
{
    public const SESSION_HANDLER_COUCHBASE = 'couchbase';
    public const SESSION_HANDLER_REDIS = 'redis';
    public const SESSION_HANDLER_REDIS_LOCKING = 'redis_locking';
    public const SESSION_HANDLER_MYSQL = 'mysql';
    public const SESSION_HANDLER_FILE = 'file';

    public const SESSION_LIFETIME_BROWSER_SESSION = '0';
    public const SESSION_LIFETIME_1_HOUR = '3600';
    public const SESSION_LIFETIME_0_5_HOUR = '1800';
    public const SESSION_LIFETIME_1_DAY = '86400';
    public const SESSION_LIFETIME_2_DAYS = '172800';
    public const SESSION_LIFETIME_3_DAYS = '259200';
    public const SESSION_LIFETIME_30_DAYS = '2592000';
    public const SESSION_LIFETIME_1_YEAR = '31536000';

    public const SESSION_IS_TEST = 'SESSION_IS_TEST';

    public const SESSION_HANDLER_REDIS_LOCKING_TIMEOUT_MILLISECONDS = 'SESSION_HANDLER_REDIS_LOCKING_TIMEOUT_MILLISECONDS';
    public const SESSION_HANDLER_REDIS_LOCKING_RETRY_DELAY_MICROSECONDS = 'SESSION_HANDLER_REDIS_LOCKING_RETRY_DELAY_MICROSECONDS';
    public const SESSION_HANDLER_REDIS_LOCKING_LOCK_TTL_MILLISECONDS = 'SESSION_HANDLER_REDIS_LOCKING_LOCK_TTL_MILLISECONDS';

    public const YVES_SESSION_SAVE_HANDLER = 'YVES_SESSION_SAVE_HANDLER';
    public const YVES_SESSION_COOKIE_NAME = 'YVES_SESSION_NAME'; // Not YVES_SESSION_COOKIE_NAME for BC reasons!
    public const YVES_SESSION_COOKIE_SECURE = 'YVES_COOKIE_SECURE'; // Not YVES_SESSION_COOKIE_SECURE for BC reasons!
    public const YVES_SESSION_COOKIE_DOMAIN = 'YVES_SESSION_COOKIE_DOMAIN';
    public const YVES_SESSION_COOKIE_PATH = 'YVES_SESSION_COOKIE_PATH';
    public const YVES_SESSION_COOKIE_TIME_TO_LIVE = 'YVES_SESSION_COOKIE_TIME_TO_LIVE';
    public const YVES_SESSION_FILE_PATH = 'YVES_SESSION_FILE_PATH';
    public const YVES_SESSION_PERSISTENT_CONNECTION = 'YVES_SESSION_PERSISTENT_CONNECTION';
    public const YVES_SESSION_TIME_TO_LIVE = 'YVES_SESSION_TIME_TO_LIVE';
    public const YVES_SSL_ENABLED = 'YVES_SSL_ENABLED';

    public const YVES_SESSION_REDIS_PROTOCOL = 'YVES_SESSION_REDIS_PROTOCOL';
    public const YVES_SESSION_REDIS_PASSWORD = 'YVES_SESSION_REDIS_PASSWORD';
    public const YVES_SESSION_REDIS_HOST = 'YVES_SESSION_REDIS_HOST';
    public const YVES_SESSION_REDIS_PORT = 'YVES_SESSION_REDIS_PORT';
    public const YVES_SESSION_REDIS_DATABASE = 'YVES_SESSION_REDIS_DATABASE';

    public const ZED_SSL_ENABLED = 'ZED_SSL_ENABLED';
    public const ZED_SESSION_SAVE_HANDLER = 'ZED_SESSION_SAVE_HANDLER';
    public const ZED_SESSION_COOKIE_NAME = 'ZED_SESSION_COOKIE_NAME';
    public const ZED_SESSION_COOKIE_SECURE = 'ZED_COOKIE_SECURE';
    public const ZED_SESSION_COOKIE_DOMAIN = 'ZED_SESSION_COOKIE_DOMAIN';
    public const ZED_SESSION_COOKIE_PATH = 'ZED_SESSION_COOKIE_PATH';
    public const ZED_SESSION_COOKIE_TIME_TO_LIVE = 'ZED_SESSION_COOKIE_TIME_TO_LIVE';
    public const ZED_SESSION_FILE_PATH = 'ZED_SESSION_FILE_PATH';
    public const ZED_SESSION_PERSISTENT_CONNECTION = 'ZED_SESSION_PERSISTENT_CONNECTION';
    public const ZED_SESSION_TIME_TO_LIVE = 'ZED_SESSION_TIME_TO_LIVE';

    public const ZED_SESSION_REDIS_PROTOCOL = 'ZED_SESSION_REDIS_PROTOCOL';
    public const ZED_SESSION_REDIS_HOST = 'ZED_SESSION_REDIS_HOST';
    public const ZED_SESSION_REDIS_PORT = 'ZED_SESSION_REDIS_PORT';
    public const ZED_SESSION_REDIS_PASSWORD = 'ZED_SESSION_REDIS_PASSWORD';
    public const ZED_SESSION_REDIS_DATABASE = 'ZED_SESSION_REDIS_DATABASE';
}
