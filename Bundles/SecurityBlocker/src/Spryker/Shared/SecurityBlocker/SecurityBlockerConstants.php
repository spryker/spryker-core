<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SecurityBlocker;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface SecurityBlockerConstants
{
    /**
     * Specification:
     * - Defines a protocol for Redis connection.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Shared\SecurityBlockerSecurityBlockerConstants::SECURITY_BLOCKER_REDIS_SCHEME} instead.
     */
    public const SECURITY_BLOCKER_REDIS_PROTOCOL = 'SECURITY_BLOCKER_REDIS:SECURITY_BLOCKER_REDIS_PROTOCOL';

    /**
     * Specification:
     * - Defines a scheme|protocol (tcp:// or redis://) for Redis connection.
     *
     * @api
     */
    public const SECURITY_BLOCKER_REDIS_SCHEME = 'SECURITY_BLOCKER_REDIS:SECURITY_BLOCKER_REDIS_SCHEME';

    /**
     * Specification:
     * - Defines a host for Redis connection.
     *
     * @api
     */
    public const SECURITY_BLOCKER_REDIS_HOST = 'SECURITY_BLOCKER_REDIS:SECURITY_BLOCKER_REDIS_HOST';

    /**
     * Specification:
     * - Defines a port for Redis connection.
     *
     * @api
     */
    public const SECURITY_BLOCKER_REDIS_PORT = 'SECURITY_BLOCKER_REDIS:SECURITY_BLOCKER_REDIS_PORT';

    /**
     * Specification:
     * - Defines a Redis database to connect to.
     *
     * @api
     */
    public const SECURITY_BLOCKER_REDIS_DATABASE = 'SECURITY_BLOCKER_REDIS:SECURITY_BLOCKER_REDIS_DATABASE';

    /**
     * Specification:
     * - Defines a password for connecting to Redis.
     *
     * @api
     */
    public const SECURITY_BLOCKER_REDIS_PASSWORD = 'SECURITY_BLOCKER_REDIS:SECURITY_BLOCKER_REDIS_PASSWORD';

    /**
     * Specification:
     * - Specifies an array of DSN strings for a multi-instance cluster/replication Redis setup.
     *
     * @api
     */
    public const SECURITY_BLOCKER_REDIS_DATA_SOURCE_NAMES = 'SECURITY_BLOCKER_REDIS:SECURITY_BLOCKER_REDIS_DATA_SOURCE_NAMES';

    /**
     * Specification:
     * - Specifies an array of connection options.
     *
     * @api
     */
    public const SECURITY_BLOCKER_REDIS_CONNECTION_OPTIONS = 'SECURITY_BLOCKER_REDIS:SECURITY_BLOCKER_REDIS_CONNECTION_OPTIONS';

    /**
     * Specification:
     * - Enables/disables data persistence for a Redis connection.
     *
     * @api
     */
    public const SECURITY_BLOCKER_REDIS_PERSISTENT_CONNECTION = 'SECURITY_BLOCKER_REDIS:SECURITY_BLOCKER_REDIS_PERSISTENT_CONNECTION';

    /**
     * Specification:
     * - Specifies the TTL configuration, the period when number of unsuccessful tries will be counted.
     *
     * @api
     */
    public const SECURITY_BLOCKER_BLOCKING_TTL = 'SECURITY_BLOCKER:BLOCKING_TTL';

    /**
     * Specification:
     * - Specifies number of failed login attempt an account can make during the `SECURITY_BLOCKER_BLOCKING_TTL` time before it is blocked.
     *
     * @api
     */
    public const SECURITY_BLOCKER_BLOCKING_NUMBER_OF_ATTEMPTS = 'SECURITY_BLOCKER:BLOCKING_NUMBER_OF_ATTEMPTS';

    /**
     * Specification:
     * - Specifies the TTL configuration, the period for which the account is blocked if the number of attempts is exceeded.
     *
     * @api
     */
    public const SECURITY_BLOCKER_BLOCK_FOR = 'SECURITY_BLOCKER:SECURITY_BLOCKER_BLOCK_FOR';

    /**
     * Specification:
     * - Specifies the TTL configuration, the period when number of unsuccessful tries will be counted for agent.
     *
     * @api
     */
    public const SECURITY_BLOCKER_AGENT_BLOCKING_TTL = 'SECURITY_BLOCKER:BLOCKING_AGENT_TTL';

    /**
     * Specification:
     * - Specifies number of failed login attempt an agent can make during the `SECURITY_BLOCKER_AGENT_BLOCKING_TTL` time before it is blocked.
     *
     * @api
     */
    public const SECURITY_BLOCKER_AGENT_BLOCKING_NUMBER_OF_ATTEMPTS = 'SECURITY_BLOCKER:BLOCKING_NUMBER_AGENT_OF_ATTEMPTS';

    /**
     * Specification:
     * - Specifies the TTL configuration, the period for which the agent is blocked if the number of attempts is exceeded for agent.
     *
     * @api
     */
    public const SECURITY_BLOCKER_AGENT_BLOCK_FOR = 'SECURITY_BLOCKER:SECURITY_BLOCKER_AGENT_BLOCK_FOR';
}
