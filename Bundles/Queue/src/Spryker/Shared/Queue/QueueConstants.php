<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Queue;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface QueueConstants
{
    /**
     * Specification:
     * - Server unique id e.g spryker-vagrant.
     *
     * @api
     */
    public const QUEUE_SERVER_ID = 'QUEUE_SERVER_ID';

    /**
     * Specification:
     * - Configuration of queue adapters and worker number as an array.
     *
     * @api
     */
    public const QUEUE_ADAPTER_CONFIGURATION = 'QUEUE_ADAPTER_CONFIGURATION';

    /**
     * Specification:
     * - The Default configuration of queue adapters and worker number as an array.
     *
     * @api
     */
    public const QUEUE_ADAPTER_CONFIGURATION_DEFAULT = 'QUEUE_ADAPTER_CONFIGURATION_DEFAULT';

    /**
     * Specification:
     * - Delay interval between each execution of worker in milliseconds.
     *
     * @api
     */
    public const QUEUE_WORKER_INTERVAL_MILLISECONDS = 'QUEUE_WORKER_INTERVAL_MILLISECONDS';

    /**
     * Specification:
     * - Delay interval between each execution of process in microsecond.
     *
     * @api
     */
    public const QUEUE_PROCESS_TRIGGER_INTERVAL_MICROSECONDS = 'QUEUE_PROCESS_TRIGGER_INTERVAL_MICROSECONDS';

    /**
     * Specification:
     * - Worker execution time in seconds.
     *
     * @api
     */
    public const QUEUE_WORKER_MAX_THRESHOLD_SECONDS = 'QUEUE_WORKER_MAX_THRESHOLD_SECONDS';

    /**
     * Specification:
     * - Absolute path to the log of all processes output which trigger by worker.
     *
     * @api
     */
    public const QUEUE_WORKER_OUTPUT_FILE_NAME = 'QUEUE_WORKER_OUTPUT_FILE_NAME';

    /**
     * Specification:
     * - This flag will use for activation or deactivation logs for queue workers.
     *
     * @api
     */
    public const QUEUE_WORKER_LOG_ACTIVE = 'QUEUE_WORKER_LOG_ACTIVE';

    /**
     * Specification:
     * - The Default consuming/receiving configuration.
     *
     * @api
     */
    public const QUEUE_DEFAULT_RECEIVER = 'QUEUE_DEFAULT_RECEIVER';

    /**
     * Specification:
     * - This option will use to check if there is at least one message in queue.
     *
     * @api
     */
    public const QUEUE_WORKER_MESSAGE_CHECK_OPTION = 'QUEUE_WORKER_MESSAGE_CHECK_OPTION';

    /**
     * Specification:
     * - This option lets the worker to run over a loop until there is no message in the queues.
     *
     * @api
     *
     * @deprecated Use `vendor/bin/console queue:worker:start --stop-only-when-empty` instead.
     */
    public const QUEUE_WORKER_LOOP = 'QUEUE_WORKER_LOOP';
}
