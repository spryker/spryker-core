<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Queue;

interface QueueConstants
{

    const QUEUE_SERVER_ID = 'QUEUE_SERVER_ID';
    const QUEUE_ADAPTER_CONFIGURATION = 'QUEUE_ADAPTER_CONFIGURATION';

    const QUEUE_WORKER_INTERVAL_MILLISECONDS = 'QUEUE_WORKER_INTERVAL_MILLISECONDS';
    const QUEUE_WORKER_MAX_THRESHOLD_SECONDS = 'QUEUE_WORKER_MAX_THRESHOLD_SECONDS';
    const QUEUE_WORKER_OUTPUT_FILE = 'QUEUE_WORKER_OUTPUT_FILE';

}
