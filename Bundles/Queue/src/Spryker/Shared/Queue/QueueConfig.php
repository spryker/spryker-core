<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Queue;

interface QueueConfig
{
    const CONFIG_QUEUE_ADAPTER = 'queue_adapter';
    const CONFIG_MAX_WORKER_NUMBER = 'max_worker_number';
}
