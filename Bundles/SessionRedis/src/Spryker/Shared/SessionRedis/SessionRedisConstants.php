<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface SessionRedisConstants
{
    public const YVES_SESSION_TIME_TO_LIVE = 'SESSION:YVES_SESSION_TIME_TO_LIVE';

    /**
     * Specification:
     * - Defines a custom configuration for \Predis\Client when used as Yves session storage.
     * - This configuration is used exclusively when set, e.g. no other storage configuration will be used for the client.
     *
     * @api
     */
    public const YVES_SESSION_PREDIS_CLIENT_CONFIGURATION = 'SESSION:YVES_SESSION_PREDIS_CLIENT_CONFIGURATION';

    /**
     * Specification:
     * - Defines custom options for \Predis\Client when used as Yves session storage.
     *
     * @api
     */
    public const YVES_SESSION_PREDIS_CLIENT_OPTIONS = 'SESSION:YVES_SESSION_PREDIS_CLIENT_OPTIONS';

    public const YVES_SESSION_REDIS_PROTOCOL = 'SESSION:YVES_SESSION_REDIS_PROTOCOL';
    public const YVES_SESSION_REDIS_PASSWORD = 'SESSION:YVES_SESSION_REDIS_PASSWORD';
    public const YVES_SESSION_REDIS_HOST = 'SESSION:YVES_SESSION_REDIS_HOST';
    public const YVES_SESSION_REDIS_PORT = 'SESSION:YVES_SESSION_REDIS_PORT';
    public const YVES_SESSION_REDIS_DATABASE = 'SESSION:YVES_SESSION_REDIS_DATABASE';

    public const ZED_SESSION_TIME_TO_LIVE = 'SESSION:ZED_SESSION_TIME_TO_LIVE';
    public const ZED_SESSION_STORAGE_CONNECTION_CONFIGURATION = 'SESSION:ZED_SESSION_STORAGE_CONNECTION_CONFIGURATION';

    /**
     * Specification:
     * - Defines a custom configuration for \Predis\Client when used as Zed session storage.
     * - This configuration is used exclusively when set, e.g. no other storage configuration will be used for the client.
     *
     * @api
     */
    public const ZED_SESSION_PREDIS_CLIENT_CONFIGURATION = 'SESSION:ZED_SESSION_PREDIS_CLIENT_CONFIGURATION';

    /**
     * Specification:
     * - Defines custom options for \Predis\Client when used as Zed session storage.
     *
     * @api
     */
    public const ZED_SESSION_PREDIS_CLIENT_OPTIONS = 'SESSION:ZED_SESSION_PREDIS_CLIENT_OPTIONS';

    public const ZED_SESSION_REDIS_PROTOCOL = 'SESSION:ZED_SESSION_REDIS_PROTOCOL';
    public const ZED_SESSION_REDIS_HOST = 'SESSION:ZED_SESSION_REDIS_HOST';
    public const ZED_SESSION_REDIS_PORT = 'SESSION:ZED_SESSION_REDIS_PORT';
    public const ZED_SESSION_REDIS_PASSWORD = 'SESSION:ZED_SESSION_REDIS_PASSWORD';
    public const ZED_SESSION_REDIS_DATABASE = 'SESSION:ZED_SESSION_REDIS_DATABASE';

    public const LOCKING_TIMEOUT_MILLISECONDS = 'SESSION:LOCKING_TIMEOUT_MILLISECONDS';
    public const LOCKING_RETRY_DELAY_MICROSECONDS = 'SESSION:LOCKING_RETRY_DELAY_MICROSECONDS';
    public const LOCKING_LOCK_TTL_MILLISECONDS = 'SESSION:LOCKING_LOCK_TTL_MILLISECONDS';
}
