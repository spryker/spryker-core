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
    /**
     * Specification:
     * - Defines a ttl value for Yves session data.
     *
     * @api
     */
    public const YVES_SESSION_TIME_TO_LIVE = 'SESSION:YVES_SESSION_TIME_TO_LIVE';

    /**
     * Specification:
     * - Defines an array of DSN strings (describing multiple instances, sentinels) to be used for Redis replication/cluster mode.
     * - This configuration is used exclusively when set, e.g. no other storage configuration will be used for the client.
     *
     * @api
     */
    public const YVES_SESSION_REDIS_DATA_SOURCE_NAMES = 'SESSION_REDIS:YVES_SESSION_REDIS_DATA_SOURCE_NAMES';

    /**
     * Specification:
     * - Defines custom client options for Redis when used as Yves session storage.
     *
     * @api
     */
    public const YVES_SESSION_REDIS_CLIENT_OPTIONS = 'SESSION:YVES_SESSION_PREDIS_CLIENT_OPTIONS';

    /**
     * Specification:
     * - Defines a protocol (tcp:// or redis://) for Redis connection when used as Yves session storage.
     *
     * @api
     */
    public const YVES_SESSION_REDIS_PROTOCOL = 'SESSION:YVES_SESSION_REDIS_PROTOCOL';

    /**
     * Specification:
     * - Defines a password for Redis connection when used as Yves session storage.
     *
     * @api
     */
    public const YVES_SESSION_REDIS_PASSWORD = 'SESSION:YVES_SESSION_REDIS_PASSWORD';

    /**
     * Specification:
     * - Defines a host for Redis connection when used as Yves session storage.
     *
     * @api
     */
    public const YVES_SESSION_REDIS_HOST = 'SESSION:YVES_SESSION_REDIS_HOST';

    /**
     * Specification:
     * - Defines a port for Redis connection when used as Yves session storage.
     *
     * @api
     */
    public const YVES_SESSION_REDIS_PORT = 'SESSION:YVES_SESSION_REDIS_PORT';

    /**
     * Specification:
     * - Defines a database for Redis connection when used as Yves session storage.
     *
     * @api
     */
    public const YVES_SESSION_REDIS_DATABASE = 'SESSION:YVES_SESSION_REDIS_DATABASE';

    /**
     * Specification:
     * - Defines a ttl value for Zed session data.
     *
     * @api
     */
    public const ZED_SESSION_TIME_TO_LIVE = 'SESSION:ZED_SESSION_TIME_TO_LIVE';

    /**
     * Specification:
     * - Defines an array of DSN strings (describing multiple instances, sentinels) to be used for Redis replication/cluster mode.
     * - This configuration is used exclusively when set, e.g. no other storage configuration will be used for the client.
     *
     * @api
     */
    public const ZED_SESSION_REDIS_DATA_SOURCE_NAMES = 'SESSION_REDIS:ZED_SESSION_REDIS_DATA_SOURCE_NAMES';

    /**
     * Specification:
     * - Defines custom options for \Predis\Client when used as Zed session storage.
     *
     * @api
     */
    public const ZED_SESSION_REDIS_CLIENT_OPTIONS = 'SESSION:ZED_SESSION_PREDIS_CLIENT_OPTIONS';

    /**
     * Specification:
     * - Defines a protocol (tcp:// or redis://) for Redis connection when used as Zed session storage.
     *
     * @api
     */
    public const ZED_SESSION_REDIS_PROTOCOL = 'SESSION:ZED_SESSION_REDIS_PROTOCOL';

    /**
     * Specification:
     * - Defines a host for Redis connection when used as Yves session storage.
     *
     * @api
     */
    public const ZED_SESSION_REDIS_HOST = 'SESSION:ZED_SESSION_REDIS_HOST';

    /**
     * Specification:
     * - Defines a port for Redis connection when used as Yves session storage.
     *
     * @api
     */
    public const ZED_SESSION_REDIS_PORT = 'SESSION:ZED_SESSION_REDIS_PORT';

    /**
     * Specification:
     * - Defines a password for Redis connection when used as Yves session storage.
     *
     * @api
     */
    public const ZED_SESSION_REDIS_PASSWORD = 'SESSION:ZED_SESSION_REDIS_PASSWORD';

    /**
     * Specification:
     * - Defines a database for Redis connection when used as Yves session storage.
     *
     * @api
     */
    public const ZED_SESSION_REDIS_DATABASE = 'SESSION:ZED_SESSION_REDIS_DATABASE';

    public const LOCKING_TIMEOUT_MILLISECONDS = 'SESSION:LOCKING_TIMEOUT_MILLISECONDS';
    public const LOCKING_RETRY_DELAY_MICROSECONDS = 'SESSION:LOCKING_RETRY_DELAY_MICROSECONDS';
    public const LOCKING_LOCK_TTL_MILLISECONDS = 'SESSION:LOCKING_LOCK_TTL_MILLISECONDS';
}
