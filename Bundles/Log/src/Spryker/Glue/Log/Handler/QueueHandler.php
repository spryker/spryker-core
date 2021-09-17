<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Log\Handler;

use Monolog\Logger;
use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Shared\Log\Handler\AbstractQueueHandler;

class QueueHandler extends AbstractQueueHandler
{
    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     *
     * @phpstan-param 100|200|250|300|400|500|550|600|non-empty-string $level
     *
     * @param \Spryker\Client\Queue\QueueClientInterface $queueClient
     * @param string $queueName
     * @param string|int $level Level or level name
     * @param bool $bubble
     */
    public function __construct(QueueClientInterface $queueClient, $queueName, $level = Logger::DEBUG, $bubble = true)
    {
        $this->queueClient = $queueClient;
        $this->queueName = $queueName;

        parent::__construct($level, $bubble);
    }
}
