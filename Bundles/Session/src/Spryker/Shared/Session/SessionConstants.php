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
    /**
     * @deprecated Will be removed without replacement.
     *
     * @api
     * @var string
     */
    public const SESSION_IS_TEST = 'SESSION:SESSION_IS_TEST';

    /**
     * @deprecated Use {@link \Spryker\Shared\SessionRedis\SessionRedisConstants::LOCKING_TIMEOUT_MILLISECONDS} instead.
     * @var string
     */
    public const SESSION_HANDLER_REDIS_LOCKING_TIMEOUT_MILLISECONDS = 'SESSION:SESSION_HANDLER_REDIS_LOCKING_TIMEOUT_MILLISECONDS';

    /**
     * @deprecated Use {@link \Spryker\Shared\SessionRedis\SessionRedisConstants::LOCKING_RETRY_DELAY_MICROSECONDS} instead.
     * @var string
     */
    public const SESSION_HANDLER_REDIS_LOCKING_RETRY_DELAY_MICROSECONDS = 'SESSION:SESSION_HANDLER_REDIS_LOCKING_RETRY_DELAY_MICROSECONDS';

    /**
     * @deprecated Use {@link \Spryker\Shared\SessionRedis\SessionRedisConstants::LOCKING_LOCK_TTL_MILLISECONDS} instead.
     * @var string
     */
    public const SESSION_HANDLER_REDIS_LOCKING_LOCK_TTL_MILLISECONDS = 'SESSION:SESSION_HANDLER_REDIS_LOCKING_LOCK_TTL_MILLISECONDS';

    /**
     * @var string
     */
    public const YVES_SESSION_SAVE_HANDLER = 'SESSION:YVES_SESSION_SAVE_HANDLER';

    /**
     * @var string
     */
    public const YVES_SESSION_COOKIE_NAME = 'SESSION:YVES_SESSION_COOKIE_NAME';

    /**
     * @var string
     */
    public const YVES_SESSION_COOKIE_SECURE = 'SESSION:YVES_SESSION_COOKIE_SECURE';

    /**
     * @var string
     */
    public const YVES_SESSION_COOKIE_DOMAIN = 'SESSION:YVES_SESSION_COOKIE_DOMAIN';

    /**
     * @var string
     */
    public const YVES_SESSION_COOKIE_PATH = 'SESSION:YVES_SESSION_COOKIE_PATH';

    /**
     * @var string
     */
    public const YVES_SESSION_COOKIE_TIME_TO_LIVE = 'SESSION:YVES_SESSION_COOKIE_TIME_TO_LIVE';

    /**
     * Specification:
     * - Allows to declare if your Yves session cookie should be restricted to a first-party or same-site context.
     * - Available since PHP 7.3.0.
     *
     * @api
     * @var string
     */
    public const YVES_SESSION_COOKIE_SAMESITE = 'SESSION:YVES_SESSION_COOKIE_SAMESITE';

    /**
     * @deprecated Use {@link \Spryker\Shared\SessionFile\SessionFileConstants::YVES_SESSION_FILE_PATH} instead.
     * @var string
     */
    public const YVES_SESSION_FILE_PATH = 'SESSION:YVES_SESSION_FILE_PATH';

    /**
     * @var string
     */
    public const YVES_SESSION_PERSISTENT_CONNECTION = 'SESSION:YVES_SESSION_PERSISTENT_CONNECTION';

    /**
     * @var string
     */
    public const YVES_SESSION_TIME_TO_LIVE = 'SESSION:YVES_SESSION_TIME_TO_LIVE';

    /**
     * @var string
     */
    public const YVES_SSL_ENABLED = 'SESSION:YVES_SSL_ENABLED';

    /**
     * @deprecated Use {@link \Spryker\Shared\SessionRedis\SessionRedisConstants::YVES_SESSION_REDIS_SCHEME} instead.
     * @var string
     */
    public const YVES_SESSION_REDIS_PROTOCOL = 'SESSION:YVES_SESSION_REDIS_PROTOCOL';

    /**
     * @deprecated Use {@link \Spryker\Shared\SessionRedis\SessionRedisConstants::YVES_SESSION_REDIS_PASSWORD} instead.
     * @var string
     */
    public const YVES_SESSION_REDIS_PASSWORD = 'SESSION:YVES_SESSION_REDIS_PASSWORD';

    /**
     * @deprecated Use {@link \Spryker\Shared\SessionRedis\SessionRedisConstants::YVES_SESSION_REDIS_HOST} instead.
     * @var string
     */
    public const YVES_SESSION_REDIS_HOST = 'SESSION:YVES_SESSION_REDIS_HOST';

    /**
     * @deprecated Use {@link \Spryker\Shared\SessionRedis\SessionRedisConstants::YVES_SESSION_REDIS_PORT} instead.
     * @var string
     */
    public const YVES_SESSION_REDIS_PORT = 'SESSION:YVES_SESSION_REDIS_PORT';

    /**
     * @deprecated Use {@link \Spryker\Shared\SessionRedis\SessionRedisConstants::YVES_SESSION_REDIS_DATABASE} instead.
     * @var string
     */
    public const YVES_SESSION_REDIS_DATABASE = 'SESSION:YVES_SESSION_REDIS_DATABASE';

    /**
     * Specification:
     * - Defines a custom configuration for \Predis\Client when used as Yves session storage.
     * - This configuration is used exclusively when set, e.g. no other storage configuration will be used for the client.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Shared\SessionRedis\SessionRedisConstants::YVES_SESSION_REDIS_DATA_SOURCE_NAMES} instead.
     * @var string
     */
    public const YVES_SESSION_PREDIS_CLIENT_CONFIGURATION = 'SESSION:YVES_SESSION_PREDIS_CLIENT_CONFIGURATION';

    /**
     * Specification:
     * - Defines custom options for \Predis\Client when used as Yves session storage.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Shared\SessionRedis\SessionRedisConstants::YVES_SESSION_REDIS_CLIENT_OPTIONS} instead.
     * @var string
     */
    public const YVES_SESSION_PREDIS_CLIENT_OPTIONS = 'SESSION:YVES_SESSION_PREDIS_CLIENT_OPTIONS';

    /**
     * @var string
     */
    public const ZED_SSL_ENABLED = 'SESSION:ZED_SSL_ENABLED';

    /**
     * @var string
     */
    public const ZED_SESSION_SAVE_HANDLER = 'SESSION:ZED_SESSION_SAVE_HANDLER';

    /**
     * @var string
     */
    public const ZED_SESSION_COOKIE_NAME = 'SESSION:ZED_SESSION_COOKIE_NAME';

    /**
     * @var string
     */
    public const ZED_SESSION_COOKIE_SECURE = 'SESSION:ZED_SESSION_COOKIE_SECURE';

    /**
     * @var string
     */
    public const ZED_SESSION_COOKIE_DOMAIN = 'SESSION:ZED_SESSION_COOKIE_DOMAIN';

    /**
     * @var string
     */
    public const ZED_SESSION_COOKIE_PATH = 'SESSION:ZED_SESSION_COOKIE_PATH';

    /**
     * @var string
     */
    public const ZED_SESSION_COOKIE_TIME_TO_LIVE = 'SESSION:ZED_SESSION_COOKIE_TIME_TO_LIVE';

    /**
     * Specification:
     * - Allows to declare if your Zed session cookie should be restricted to a first-party or same-site context.
     * - Available since PHP 7.3.0.
     *
     * @api
     * @var string
     */
    public const ZED_SESSION_COOKIE_SAMESITE = 'SESSION:ZED_SESSION_COOKIE_SAMESITE';

    /**
     * @deprecated Use {@link \Spryker\Shared\SessionFile\SessionFileConstants::ZED_SESSION_FILE_PATH} instead.
     * @var string
     */
    public const ZED_SESSION_FILE_PATH = 'SESSION:ZED_SESSION_FILE_PATH';

    /**
     * @var string
     */
    public const ZED_SESSION_PERSISTENT_CONNECTION = 'SESSION:ZED_SESSION_PERSISTENT_CONNECTION';

    /**
     * @var string
     */
    public const ZED_SESSION_TIME_TO_LIVE = 'SESSION:ZED_SESSION_TIME_TO_LIVE';

    /**
     * @deprecated Use {@link \Spryker\Shared\SessionRedis\SessionRedisConstants::ZED_SESSION_REDIS_SCHEME} instead.
     * @var string
     */
    public const ZED_SESSION_REDIS_PROTOCOL = 'SESSION:ZED_SESSION_REDIS_PROTOCOL';

    /**
     * @deprecated Use {@link \Spryker\Shared\SessionRedis\SessionRedisConstants::ZED_SESSION_REDIS_HOST} instead.
     * @var string
     */
    public const ZED_SESSION_REDIS_HOST = 'SESSION:ZED_SESSION_REDIS_HOST';

    /**
     * @deprecated Use {@link \Spryker\Shared\SessionRedis\SessionRedisConstants::ZED_SESSION_REDIS_PORT} instead.
     * @var string
     */
    public const ZED_SESSION_REDIS_PORT = 'SESSION:ZED_SESSION_REDIS_PORT';

    /**
     * @deprecated Use {@link \Spryker\Shared\SessionRedis\SessionRedisConstants::ZED_SESSION_REDIS_PASSWORD} instead.
     * @var string
     */
    public const ZED_SESSION_REDIS_PASSWORD = 'SESSION:ZED_SESSION_REDIS_PASSWORD';

    /**
     * @deprecated Use {@link \Spryker\Shared\SessionRedis\SessionRedisConstants::ZED_SESSION_REDIS_DATABASE} instead.
     * @var string
     */
    public const ZED_SESSION_REDIS_DATABASE = 'SESSION:ZED_SESSION_REDIS_DATABASE';

    /**
     * Specification:
     * - Defines a custom configuration for \Predis\Client when used as Zed session storage.
     * - This configuration is used exclusively when set, e.g. no other storage configuration will be used for the client.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Shared\SessionRedis\SessionRedisConstants::ZED_SESSION_REDIS_DATA_SOURCE_NAMES} instead.
     * @var string
     */
    public const ZED_SESSION_PREDIS_CLIENT_CONFIGURATION = 'SESSION:ZED_SESSION_PREDIS_CLIENT_CONFIGURATION';

    /**
     * Specification:
     * - Defines custom options for \Predis\Client when used as Zed session storage.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Shared\SessionRedis\SessionRedisConstants::ZED_SESSION_REDIS_CLIENT_OPTIONS} instead.
     * @var string
     */
    public const ZED_SESSION_PREDIS_CLIENT_OPTIONS = 'SESSION:ZED_SESSION_PREDIS_CLIENT_OPTIONS';
}
