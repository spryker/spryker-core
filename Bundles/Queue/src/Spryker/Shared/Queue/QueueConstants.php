<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Queue;

interface QueueConstants
{
    const QUEUE_SERVER_ID = 'QUEUE_SERVER_ID';
    const QUEUE_ADAPTOR_NAME_MAPPING = 'QUEUE_ADAPTOR_NAME_MAPPING';
    const QUEUE_ADAPTOR_NAME_DEFAULT = 'QUEUE_ADAPTOR_NAME_DEFAULT';

    const QUEUE_HOST = 'QUEUE_HOST';
    const QUEUE_PORT = 'QUEUE_PORT';
    const QUEUE_USERNAME = 'QUEUE_USERNAME';
    const QUEUE_PASSWORD = 'QUEUE_PASSWORD';

    const QUEUE_WORKER_PROCESSOR = 'QUEUE_WORKER_PROCESSOR';
    const QUEUE_WORKER_INTERVAL_MILLISECONDS = 'QUEUE_WORKER_INTERVAL_MILLISECONDS';
    const QUEUE_WORKER_MAX_THRESHOLD_SECONDS = 'QUEUE_WORKER_MAX_THRESHOLD_SECONDS';
    const QUEUE_WORKER_OUTPUT_FILE = 'QUEUE_WORKER_OUTPUT_FILE';
}
