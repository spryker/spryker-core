<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Queue;

interface QueueConstants
{
    /**
     * Specification:
     * - Server unique id e.g spryker-vagrant
     *
     * @api
     */
    const QUEUE_SERVER_ID = 'QUEUE_SERVER_ID';

    /**
     * Specification:
     * - Configuration of queue adapters and worker number as an array
     *
     * @api
     */
    const QUEUE_ADAPTER_CONFIGURATION = 'QUEUE_ADAPTER_CONFIGURATION';

    /**
     * Specification:
     * - The Default configuration of queue adapters and worker number as an array
     *
     * @api
     */
    const QUEUE_ADAPTER_CONFIGURATION_DEFAULT = 'QUEUE_ADAPTER_CONFIGURATION_DEFAULT';

    /**
     * Specification:
     * - Delay interval between each execution of worker in milliseconds
     *
     * @api
     */
    const QUEUE_WORKER_INTERVAL_MILLISECONDS = 'QUEUE_WORKER_INTERVAL_MILLISECONDS';

    /**
     * Specification:
     * - Worker execution time in seconds
     *
     * @api
     */
    const QUEUE_WORKER_MAX_THRESHOLD_SECONDS = 'QUEUE_WORKER_MAX_THRESHOLD_SECONDS';

    /**
     * Specification:
     * - Absolute path to the log of all processes output which trigger by worker
     *
     * @api
     */
    const QUEUE_WORKER_OUTPUT_FILE_NAME = 'QUEUE_WORKER_OUTPUT_FILE_NAME';

    /**
     * Specification:
     * - This flag will use for activation or deactivation logs for queue workers
     *
     * @api
     */
    const QUEUE_WORKER_LOG_ACTIVE = 'QUEUE_WORKER_LOG_ACTIVE';

    /**
     * Specification:
     * - The Default consuming/receiving configuration
     *
     * @api
     */
    const QUEUE_DEFAULT_RECEIVER = 'QUEUE_DEFAULT_RECEIVER';

    /**
     * Specification:
     * - This option will use to check if there is at least one message in queue
     *
     * @api
     */
    const QUEUE_WORKER_MESSAGE_CHECK_OPTION = 'QUEUE_WORKER_MESSAGE_CHECK_OPTION';
}
