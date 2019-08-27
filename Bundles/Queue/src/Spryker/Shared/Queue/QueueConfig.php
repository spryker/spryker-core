<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Queue;

interface QueueConfig
{
    public const CONFIG_QUEUE_ADAPTER = 'queue_adapter';
    public const CONFIG_MAX_WORKER_NUMBER = 'max_worker_number';
    public const CONFIG_QUEUE_OPTION_NO_ACK = 'noAck';
    public const CONFIG_WORKER_STOP_ONLY_WHEN_EMPTY = 'stop_only_when_empty';
}
