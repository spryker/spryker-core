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
     * - Server unique id e.g spryker-{hostname}.
     *
     * @api
     *
     * @var string
     */
    public const QUEUE_SERVER_ID = 'QUEUE_SERVER_ID';

    /**
     * Specification:
     * - Configuration of queue adapters and worker number as an array.
     *
     * @api
     *
     * @var string
     */
    public const QUEUE_ADAPTER_CONFIGURATION = 'QUEUE_ADAPTER_CONFIGURATION';

    /**
     * Specification:
     * - The Default configuration of queue adapters and worker number as an array.
     *
     * @api
     *
     * @var string
     */
    public const QUEUE_ADAPTER_CONFIGURATION_DEFAULT = 'QUEUE_ADAPTER_CONFIGURATION_DEFAULT';

    /**
     * Specification:
     * - Delay interval between each execution of worker in milliseconds.
     *
     * @api
     *
     * @var string
     */
    public const QUEUE_WORKER_INTERVAL_MILLISECONDS = 'QUEUE_WORKER_INTERVAL_MILLISECONDS';

    /**
     * Specification:
     * - Delay interval between each execution of process in microsecond.
     *
     * @api
     *
     * @var string
     */
    public const QUEUE_PROCESS_TRIGGER_INTERVAL_MICROSECONDS = 'QUEUE_PROCESS_TRIGGER_INTERVAL_MICROSECONDS';

    /**
     * Specification:
     * - Worker execution time in seconds.
     *
     * @api
     *
     * @var string
     */
    public const QUEUE_WORKER_MAX_THRESHOLD_SECONDS = 'QUEUE_WORKER_MAX_THRESHOLD_SECONDS';

    /**
     * Specification:
     * - Absolute path to the log of all processes output which trigger by worker.
     *
     * @api
     *
     * @var string
     */
    public const QUEUE_WORKER_OUTPUT_FILE_NAME = 'QUEUE_WORKER_OUTPUT_FILE_NAME';

    /**
     * Specification:
     * - This flag will use for activation or deactivation logs for queue workers.
     *
     * @api
     *
     * @var string
     */
    public const QUEUE_WORKER_LOG_ACTIVE = 'QUEUE_WORKER_LOG_ACTIVE';

    /**
     * Specification:
     * - The Default consuming/receiving configuration.
     *
     * @api
     *
     * @var string
     */
    public const QUEUE_DEFAULT_RECEIVER = 'QUEUE_DEFAULT_RECEIVER';

    /**
     * Specification:
     * - This option will use to check if there is at least one message in queue.
     *
     * @api
     *
     * @var string
     */
    public const QUEUE_WORKER_MESSAGE_CHECK_OPTION = 'QUEUE_WORKER_MESSAGE_CHECK_OPTION';

    /**
     * Specification:
     * - This option lets the worker to run over a loop until there is no message in the queues.
     *
     * @api
     *
     * @deprecated Use `vendor/bin/console queue:worker:start --stop-only-when-empty` instead.
     *
     * @var string
     */
    public const QUEUE_WORKER_LOOP = 'QUEUE_WORKER_LOOP';

    /**
     * Specification:
     * - Configuration of chunk size for queue message retrieval.
     * - Example: $config[QueueConstants::QUEUE_MESSAGE_CHUNK_SIZE_MAP] = ['queueName' => 100].
     *
     * @api
     *
     * @var string
     */
    public const QUEUE_MESSAGE_CHUNK_SIZE_MAP = 'QUEUE:QUEUE_MESSAGE_CHUNK_SIZE_MAP';

    /**
     * Specification:
     * - Recommended (optimal) memory of queue task chunk size for event message processing in KB.
     * - Used to log a warning if the task chunk data size exceeds this limit.
     * - Example: $config[QueueConstants::MAX_QUEUE_TASK_MEMORY_CHUNK_SIZE] = 1024 (1024 KB).
     *
     * @api
     *
     * @var string
     */
    public const MAX_QUEUE_TASK_MEMORY_CHUNK_SIZE = 'QUEUE:MAX_QUEUE_TASK_MEMORY_CHUNK_SIZE';

    /**
     * Specification:
     * - Recommended (optimal) memory limit for the entire queue task process in MB.
     * - Used to log a warning if the task exceeds this memory limit.
     * - The memory value should be chosen based on the total scheduler memory and the count of workers.
     * - Example: $config[QueueConstants::MAX_QUEUE_TASK_MEMORY_SIZE] = 1024 (1024 MB).
     *
     * @api
     *
     * @var string
     */
    public const MAX_QUEUE_TASK_MEMORY_SIZE = 'QUEUE:MAX_QUEUE_TASK_MEMORY_SIZE';

    /**
     * Specification:
     * - Whether wait limiting feature is enabled or not
     *
     * @api
     *
     * @var string
     */
    public const QUEUE_WORKER_WAIT_LIMIT_ENABLED = 'QUEUE:QUEUE_WORKER_WAIT_LIMIT_ENABLED';

    /**
     * Specification:
     * - Defines maximum waiting time in seconds for a pending queue worker process.
     *
     * @api
     *
     * @var string
     */
    public const QUEUE_WORKER_MAX_WAITING_SECONDS = 'QUEUE:QUEUE_WORKER_MAX_WAITING_SECONDS';

    /**
     * Specification:
     * - Defines maximum waiting rounds for a pending queue worker process.
     *
     * @api
     *
     * @var string
     */
    public const QUEUE_WORKER_MAX_WAITING_ROUNDS = 'QUEUE:QUEUE_WORKER_MAX_WAITING_ROUNDS';
}
