<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business\Reader;

interface QueueConfigReaderInterface
{
    /**
     * @param string $queueName
     *
     * @return int
     */
    public function getMaxQueueWorkerByQueueName(string $queueName): int;
}
