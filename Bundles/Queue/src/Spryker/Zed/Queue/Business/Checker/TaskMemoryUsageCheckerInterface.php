<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business\Checker;

interface TaskMemoryUsageCheckerInterface
{
    /**
     * @param string $queueName
     * @param list<\Generated\Shared\Transfer\QueueReceiveMessageTransfer> $messages
     * @param int $chunkSize
     *
     * @return void
     */
    public function check(string $queueName, array $messages, int $chunkSize): void;
}
